<?php
declare(strict_types=1);

namespace MKSpark\Controllers;

use PDO;

class RegistrationController
{
    private PDO $db;
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
        $dsn = sprintf(
            'mysql:host=%s;dbname=%s;charset=utf8mb4',
            $config['db']['host'],
            $config['db']['dbname']
        );
        $this->db = new PDO($dsn, $config['db']['user'], $config['db']['pass']);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function register(array $data): array
    {
        // Provera obaveznih polja
        foreach (['fullName', 'dateOfBirth', 'address', 'contactPhone', 'email', 'raceTerm', 'startingNumber'] as $field) {
            if (empty($data[$field])) {
                return ['success' => false, 'message' => "Field {$field} is required."];
            }
        }

        // Validacija starosti (18+)
        $dob = new \DateTime($data['dateOfBirth']);
        $today = new \DateTime();
        $age = $today->diff($dob)->y;
        if ($age < 18) {
            return ['success' => false, 'message' => 'Registrant must be at least 18 years old.'];
        }

        // Validacija važenja vozačke dozvole u odnosu na termin trke
        $licenseValidUntil = new \DateTime($data['licenseValidUntil']);
        $raceTermDate = new \DateTime($data['raceTermDate']);
        if ($licenseValidUntil < $raceTermDate) {
            return ['success' => false, 'message' => 'License valid until must be later than the race term date.'];
        }

        // Provera jedinstvenosti broja starta
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM registrants WHERE starting_number = :starting_number');
        $stmt->execute(['starting_number' => $data['startingNumber']]);
        if ((int) $stmt->fetchColumn() > 0) {
            $stmt = $this->db->query('SELECT MAX(starting_number) AS max_num FROM registrants');
            $maxNum = (int) $stmt->fetch(PDO::FETCH_ASSOC)['max_num'];
            $data['startingNumber'] = $maxNum + 1;
            $autoAssigned = true;
        } else {
            $autoAssigned = false;
        }

        // Unos podataka o učesniku
        $sql = 'INSERT INTO registrants (
                    full_name, address, date_of_birth, contact_phone, email, motorcycle,
                    engine_displacement, driving_license_number, license_valid_until, race_term,
                    starting_number, competitive_license, racing_group, confirmation_diabetes, confirmation_risk
                ) VALUES (
                    :full_name, :address, :date_of_birth, :contact_phone, :email, :motorcycle,
                    :engine_displacement, :driving_license_number, :license_valid_until, :race_term,
                    :starting_number, :competitive_license, :racing_group, :confirmation_diabetes, :confirmation_risk
                )';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'full_name'            => $data['fullName'],
            'address'              => $data['address'],
            'date_of_birth'        => $data['dateOfBirth'],
            'contact_phone'        => $data['contactPhone'],
            'email'                => $data['email'],
            'motorcycle'           => $data['motorcycle'] ?? '',
            'engine_displacement'  => $data['engineDisplacement'] ?? '',
            'driving_license_number' => $data['licenseNumber'] ?? '',
            'license_valid_until'  => $data['licenseValidUntil'],
            'race_term'            => $data['raceTerm'],
            'starting_number'      => $data['startingNumber'],
            'competitive_license'  => $data['competitiveLicense'] ?? 'no',
            'racing_group'         => $data['racingGroup'] ?? '',
            'confirmation_diabetes' => $data['confirmDiabetes'] ? 1 : 0,
            'confirmation_risk'     => $data['confirmRisk'] ? 1 : 0,
        ]);

        $termDateFormatted = (new \DateTime($data['raceTermDate']))->format('d-m-Y');
        $referenceNumber = "97-{$termDateFormatted}/{$data['startingNumber']}";
        $this->db->prepare('UPDATE registrants SET reference_number = :ref WHERE id = :id')
            ->execute(['ref' => $referenceNumber, 'id' => $this->db->lastInsertId()]);

        $this->sendConfirmationEmail($data, $referenceNumber);

        return [
            'success' => true,
            'message' => $autoAssigned
                ? "Registration successful. Your starting number was auto-assigned to {$data['startingNumber']}."
                : 'Registration successful.'
        ];
    }

    private function sendConfirmationEmail(array $data, string $referenceNumber): void
    {
        $subject = 'MKSpark Payment Instructions';
        $termDate = (new \DateTime($data['raceTermDate']))->format('d-m-Y');
        $body = <<<EOT
Registration for race term: {$data['raceTerm']}
Payment instructions in RSD:
Payer: {$data['fullName']}, {$data['address']}
Recipient: Moto Udruženje MK SPARK, Višnjička 59V-Beograd
Recipient Account: 160-0000000529543-16
Reference Number: 97-{$termDate} / {$data['startingNumber']}
Payment Purpose: Registration fee for Spark Track Day
Amount: 12.000,00
Currency: RSD

53, 54 or 56: Correspondent bank BCITITMM INTESA SANPAOLO SPA MILANO ITALY  
57: Account With Institution: DBDBRSBG BANCA INTESA AD BEOGRAD MILENTIJA POPOVICA  
7B 11070 NOVI BEOGRAD SERBIA, REPUBLIC OF  
59: Beneficiary Customer: RS35160005280000626044 EUR MK SPARK VISNJICKA 59V 4  
59 BEOGRAD-PALILULA, SERBIA, REPUBLIC OF

Please follow the payment instructions and contact us if you have any questions.
EOT;

        $headers = "From: test@milosu.com\r\n";
        mail($data['email'], $subject, $body, $headers);
    }
}

import Vue from 'vue';
import VueI18n from 'vue-i18n';

Vue.use(VueI18n);

const messages = {
  en: {
    registrationForm: {
      fullName: 'Full Name',
      address: 'Address',
      dob: 'Date of Birth',
      contactPhone: 'Contact Phone',
      email: 'Email Address',
      motorcycle: 'Motorcycle & Engine Displacement',
      licenseNumber: 'Driving License Number',
      licenseValid: 'License Valid Until',
      raceTerm: 'Race Term Registration',
      startingNumber: 'Starting Number',
      competitiveLicense: 'Possession of a Competitive License',
      racingGroup: 'Racing Group',
      diabetesConfirmation: 'I confirm that I do not suffer from diabetes, epilepsy, or any similar condition.',
      riskConfirmation: 'I confirm that I drive at my own risk...',
      submit: 'Submit Registration'
    }
  },
  sr: {
    registrationForm: {
      fullName: 'Ime i Prezime',
      address: 'Adresa',
      dob: 'Datum rođenja',
      contactPhone: 'Kontakt telefon',
      email: 'Email adresa',
      motorcycle: 'Motorcycle & Zapremina motora',
      licenseNumber: 'Broj vozačke dozvole',
      licenseValid: 'Važi do',
      raceTerm: 'Termin trke',
      startingNumber: 'Broj starta',
      competitiveLicense: 'Posedujete takmičarsku licencu',
      racingGroup: 'Takmičarska grupa',
      diabetesConfirmation: 'Potvrđujem da ne bolujem od dijabetesa, epilepsije ili sličnih stanja.',
      riskConfirmation: 'Potvrđujem da vozim na sopstvenu odgovornost...',
      submit: 'Pošalji prijavu'
    }
  }
};

const i18n = new VueI18n({
  locale: 'en',
  fallbackLocale: 'en',
  messages
});

export default i18n;

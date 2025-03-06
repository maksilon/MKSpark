<template>
    <div class="container mt-5">
      <h1>{{ $t('registrationForm.submit') }}</h1>
      <form @submit.prevent="handleSubmit">
        <div class="form-group mb-3">
          <label for="fullName">{{ $t('registrationForm.fullName') }} *</label>
          <input
            id="fullName"
            type="text"
            class="form-control"
            v-model="form.fullName"
            required
          />
        </div>
        <div class="form-group mb-3">
          <label for="dob">{{ $t('registrationForm.dob') }} *</label>
          <input
            id="dob"
            type="date"
            class="form-control"
            v-model="form.dateOfBirth"
            required
          />
        </div>
        <!-- Ostala polja: address, contactPhone, email, motorcycle, itd. -->
        <div class="form-check mb-3">
          <input
            id="confirmDiabetes"
            type="checkbox"
            class="form-check-input"
            v-model="form.confirmDiabetes"
            required
          />
          <label class="form-check-label" for="confirmDiabetes">
            {{ $t('registrationForm.diabetesConfirmation') }}
          </label>
        </div>
        <button type="submit" class="btn btn-primary">
          {{ $t('registrationForm.submit') }}
        </button>
      </form>
      <!-- Prekidač jezika -->
      <div class="mt-3">
        <select v-model="$i18n.locale" class="form-control" style="width: 150px;">
          <option value="en">English</option>
          <option value="sr">Srpski</option>
        </select>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    name: 'RegistrationForm',
    data() {
      return {
        form: {
          fullName: '',
          dateOfBirth: '',
          confirmDiabetes: false,
          // Ostala polja
        }
      };
    },
    methods: {
      handleSubmit() {
        // Validacija, npr. provera starosti, validnosti dozvole itd.
        fetch('/back-end/index.php?action=register', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form)
        })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Registration successful! Please check your email.');
            } else {
              alert('Error: ' + data.message);
            }
          });
      }
    }
  };
  </script>
  
  <style lang="scss" scoped>
  /* SCSS stilovi, prema VueJS style guide-u */
  </style>
  
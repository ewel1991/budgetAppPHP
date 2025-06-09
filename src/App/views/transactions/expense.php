<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3">
    <form class="form-wrapper" method="POST">

      <?php include $this->resolve("partials/_csrf.php"); ?>

      <!-- Kwota -->
      <div class="form-group row mb-4">
        <label for="amount" class="form-label input1">Kwota:</label>
        <input value="<?php echo e($oldFormData['amount'] ?? ''); ?>"
          type="number"
          class="form-control rounded-3 py-2 <?= isset($errors['amount']) ? 'is-invalid' : '' ?>"
          id="amount"
          name="amount"
          step="0.01"
          min="0"
          placeholder="Wpisz kwotę"
          title="<?= isset($errors['amount']) ? e($errors['amount'][0]) : ''; ?>">


      </div>

      <!-- Data -->
      <div class="form-group row mb-4">
        <label for="date" class="form-label input1">Data:</label>
        <input value="<?php echo e($oldFormData['date'] ?? ''); ?>"
          type="date"
          class="form-control rounded-3 py-2 <?= isset($errors['date']) ? 'is-invalid' : '' ?>"
          id="date"
          name="date"
          required
          title="<?= isset($errors['date']) ? e($errors['date'][0]) : ''; ?>">


      </div>

      <!-- Sposób płatności -->
      <div class="form-group row mb-4">
        <label for="category" class="form-label input1">Sposób płatności:</label>
        <select class="form-select rounded-3 py-2" id="payment" name="payment">
          <option value="gotówka">Gotówka</option>
          <option value="karta debetowa">Karta debetowa</option>
          <option value="karta kredytowa">Karta kredytowa</option>
        </select>
      </div>


      <!-- Kategoria -->
      <div class="form-group row mb-4">
        <label for="category" class="form-label input1">Kategoria:</label>
        <select class="form-select rounded-3 py-2" id="category" name="category">
          <option value="jedzenie">Jedzenie</option>
          <option value="mieszkanie">Mieszkanie</option>
          <option value="transport">Transport</option>
          <option value="telekomunikacja">Telekomunikacja</option>
          <option value="opieka zdrowotna">Opieka zdrowotna</option>
          <option value="ubranie">Ubranie</option>
          <option value="higiena">Higiena</option>
          <option value="dzieci">Dzieci</option>
          <option value="rozrywka">Rozrywka</option>
          <option value="wycieczka">Wycieczka</option>
          <option value="szkolenia">Szkolenia</option>
          <option value="książki">Książki</option>
          <option value="oszczędności">Oszczędności</option>
          <option value="emerytura">Emerytura</option>
          <option value="spłata długów">Spłata długów</option>
          <option value="darowizna">Darowizna</option>
          <option value="inne wydatki">Inne wydatki</option>
        </select>
      </div>

      <!-- Komentarz -->
      <div class="form-group row mb-4">
        <label for="comment" class="form-label input1">Komentarz:</label>
        <textarea class="form-control rounded-3 py-2" id="comment" name="comment" rows="3" placeholder="Dodaj komentarz..."></textarea>
      </div>

      <!-- Przyciski: Zapisz i Anuluj -->
      <div class="d-flex gap-3 justify-content-center">
        <button type="submit" class="btn btn-brown py-3 px-4 rounded-3">Zapisz</button>
        <a href="index.php" class="btn btn-brown py-3 px-4 rounded-3">Anuluj</a>
      </div>

    </form>
  </div>

  </div>


  <?php include $this->resolve("partials/_footer.php"); ?>
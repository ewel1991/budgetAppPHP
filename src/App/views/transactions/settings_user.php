<?php include $this->resolve("partials/_header.php"); ?>

<body>
  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3 py-4">


    <form method="POST" action="/settings/update-name">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="name" class="form-label">Zmień imię:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3">
        <input
          type="text"
          class="form-control"
          id="name"
          name="name"
          required
          value="<?= htmlspecialchars($currentUser['username']) ?>"
          placeholder="Nowe imię">

        <button type="submit" class="btn btn-brownish px-4">Zapisz</button>
      </div>
    </form>

    <form method="POST" action="/settings/update-email">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="email" class="form-label">Zmień e-mail:</label>

      <div class="d-flex gap-2 align-items-stretch mb-3">
        <input
          type="email"
          class="form-control"
          id="email"
          name="email"
          required
          value="<?= htmlspecialchars($currentUser['email']) ?>"
          placeholder="Nowy e-mail">

        <button type="submit" class="btn btn-brownish px-4">Zapisz</button>
      </div>
    </form>

    <form method="POST" action="/settings/update-password">
      <?php include $this->resolve("partials/_csrf.php"); ?>

      <label for="password" class="form-label">Nowe hasło:</label>
      <input
        type="password"
        class="form-control mb-3"
        id="password"
        name="password"
        required
        placeholder="Nowe hasło">

      <label for="password_confirm" class="form-label">Powtórz hasło:</label>
      <input
        type="password"
        class="form-control mb-3"
        id="password_confirm"
        name="password_confirm"
        required
        placeholder="Powtórz hasło">

      <button type="submit" class="btn btn-brownish px-4">Zmień hasło</button>
    </form>




  </div>

  <?php include $this->resolve("partials/_footer.php"); ?>
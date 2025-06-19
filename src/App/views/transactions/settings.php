<?php include $this->resolve("partials/_header.php"); ?>

<body>
  <?php include $this->resolve("partials/_navbar.php"); ?>

  <div class="small-container text-white rounded-3 py-4 text-brownish">
    <h2 class="mb-4">Ustawienia</h2>

    <ul class="list-group">
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <a href="/settings/user" class="text-decoration-none text-brown">Ustawienia użytkownika</a>
        <span class="badge bg-brownish">→</span>
      </li>
      <li class="list-group-item d-flex justify-content-between align-items-center">
        <a href="/settings/transactions" class="text-decoration-none text-brown">Ustawienia transakcji</a>
        <span class="badge bg-brownish">→</span>
      </li>
    </ul>
  </div>

  <?php include $this->resolve("partials/_footer.php"); ?>
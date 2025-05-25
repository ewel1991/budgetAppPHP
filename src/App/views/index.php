<?php include $this->resolve("partials/_header.php"); ?>


<body>
  <div class="container-half">
    <div class="d-flex justify-content-center align-items-center px-4 w-100">
      <div class="w-50 px-4 py-5">
        <div class="text-overlay p-5 rounded-5">
          <h1 class="display-5 fw-bold text-white lh-1 mb-5 text-center">Ogarnij swój budżet</h1>
          <p class="lead text-white text-center">Zadbaj o swoje finanse w prosty i przejrzysty sposób. Nasza aplikacja pomoże Ci śledzić wydatki, planować budżet i osiągać finansowe cele. Niezależnie od tego, czy oszczędzasz na wakacje, czy po prostu chcesz mieć kontrolę nad domowym budżetem – jesteś we właściwym miejscu.</p>
          <div class="d-grid gap-1 gap-md-5 d-md-flex justify-content-md-center">
            <a href="/login" class="lead btn btn-brown btn-lg px-4 me-md-2">Zaloguj się</a>
            <a href="/register" class="lead btn btn-brown btn-lg">Zarejestruj się</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include $this->resolve("partials/_footer.php"); ?>
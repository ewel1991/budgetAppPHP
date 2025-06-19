<?php include $this->resolve("partials/_header.php"); ?>

<body>

  <div class="container-form">
    <div class="d-flex align-items-center position-fixed rounded-4">

      <div class="me-auto vol-lg-8 rounded-4">
        <div class="text-overlay rounded-4 shadow">
          <div class="p-5 pb-4 border-bottom-0">
            <h1 class="fw-bold mb-0 fs-2 text-white">Zaloguj się</h1>
            <a href="/" class="btn-close custom-close ms-auto" aria-label="Close"></a>
          </div>

          <div class="p-5 pt-0">
            <form method="POST" action="/login" novalidate>

              <?php include $this->resolve('partials/_csrf.php'); ?>

              <div class="form-floating mb-3">
                <input
                  value="<?php echo e($oldFormData['email'] ?? ''); ?>"
                  type="text"
                  name="email"
                  class="form-control rounded-3 <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                  id="floatingInput"
                  placeholder="name@example.com"
                  title="<?= isset($errors['email']) ? e($errors['email'][0]) : '' ?>"
                  required>

                <label for="floatingInput"> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-envelope me-3" viewBox="0 0 16 16">
                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 2.383-4.708 2.825L15 11.105zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741M1 11.105l4.708-2.897L1 5.383z" />
                  </svg>Adres e-mail</label>
              </div>


              <div class="form-floating mb-3">
                <input
                  type="password"
                  name="password"
                  class="form-control rounded-3 <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                  id="floatingPassword"
                  placeholder="Password"
                  title="<?= isset($errors['password']) ? e($errors['password'][0]) : '' ?>">

                <label for="floatingPassword"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-lock me-3" viewBox="0 0 16 16">
                    <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2M5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1" />
                  </svg>Hasło</label>
              </div>

              <button class="w-100 py-3 mb-2 btn btn-brown rounded-3" type="submit" name="submit">Zaloguj się</button>


            </form>



          </div>
        </div>
      </div>
    </div>
  </div>


  <?php include $this->resolve("partials/_footer.php"); ?>
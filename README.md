# 💰 Aplikacja do Zarządzania Finansami z Doradcą AI

## 📌 Opis
Aplikacja webowa w PHP do zarządzania budżetem osobistym.  
Pozwala rejestrować przychody i wydatki, dzielić je na kategorie, ustawiać limity wydatków oraz analizować saldo w różnych zakresach czasu.  
Wbudowany doradca finansowy (Gemini API) generuje spersonalizowane porady na podstawie:
- wysokości przychodów i wydatków
- struktury kategorii finansowych
- ustalonych limitów

---

## ✨ Funkcje
- Dodawanie, edytowanie i usuwanie **przychodów** oraz **wydatków**
- Kategorie przychodów i wydatków z możliwością samodzielnego dodawania
- Wybór metod płatności
- Ustalanie **limitów wydatków** na poszczególne kategorie
- Wyświetlanie salda w różnych zakresach dat:
  - bieżący miesiąc
  - poprzedni miesiąc
  - bieżący rok
  - własny przedział
- Analiza wydatków i przychodów w podziale na kategorie
- Generowanie krótkich porad finansowych w języku polskim z wykorzystaniem **Gemini API**

---

## 🛠 Wymagania
- PHP 8.1+
- Composer
- Serwer WWW (np. Apache, Nginx)
- Baza danych MySQL/MariaDB
- Konto Google AI z dostępem do **Gemini API**
- Klucz API zapisany w zmiennej środowiskowej `GEMINI_API_KEY`

---

## 🚀 Instalacja
1. **Sklonuj repozytorium**
   ```bash
   git clone https://github.com/twoj-login/twoj-projekt.git
   cd twoj-projekt
   ```

2. **Zainstaluj zależności**
   ```bash
   composer install
   ```

3. **Skonfiguruj bazę danych**
   - Utwórz pustą bazę w MySQL/MariaDB
   - Zaimportuj strukturę tabel (`database.sql` jeśli istnieje w repozytorium)

4. **Skonfiguruj zmienne środowiskowe**
   Utwórz plik `.env` w katalogu głównym:
   ```env
   DB_HOST=localhost
   DB_NAME=twoja_baza
   DB_USER=twoj_user
   DB_PASS=twoje_haslo

   GEMINI_API_KEY=twój_klucz_api
   ```

5. **Uruchom aplikację**
   Jeśli używasz wbudowanego serwera PHP:
   ```bash
   php -S localhost:8000 -t public
   ```
   Następnie wejdź w przeglądarce na:
   ```
   http://localhost:8000
   ```


## 🤖 Doradca Finansowy (Gemini API)
Aplikacja korzysta z modelu **`gemini-2.5-flash`** do generowania porad finansowych.  
Porada jest generowana na podstawie:
- sumarycznych kwot przychodów i wydatków
- bilansu
- udziału poszczególnych kategorii w strukturze budżetu

**Przykład promptu wysyłanego do API:**
```
Jesteś doradcą finansowym. Na podstawie danych użytkownika:
Przychody: 5000 zł (Pensja: 4000 zł, Dodatkowe: 1000 zł),
Wydatki: 4200 zł (Żywność: 1500 zł, Transport: 800 zł, Rozrywka: 700 zł),
Bilans: 800 zł.
Przeanalizuj strukturę wydatków i przychodów, wskaż mocne i słabe strony oraz zaproponuj jedną praktyczną poradę.
Odpowiedź w języku polskim, maksymalnie 3 zdania.
```

---

## 🧪 Testowanie
- Sprawdź dodawanie przychodów i wydatków
- Ustaw limity wydatków i spróbuj je przekroczyć
- Przeglądaj bilans w różnych zakresach czasu
- Obserwuj, czy doradca finansowy reaguje na zmiany struktury budżetu

---

## 📜 Licencja
Projekt udostępniony na licencji MIT. Możesz go dowolnie modyfikować i używać, także komercyjnie

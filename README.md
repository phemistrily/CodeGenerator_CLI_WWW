# CodeGenerator_CLI_WWW

# Uruchomienie

Plik startowy zawiera się w

> /public_html/index.php

Przykładowy vhost:

> \<VirtualHost \\\*:80>  
> DocumentRoot "PATH\CodeGenerator_CLI_WWW\public_html"  
> ServerName code.localhost  
> ErrorLog "logs/code-error.log"  
> CustomLog "logs/code-access.log" common  
> \<\/VirtualHost>

## Wymagania

W folderze głównym aplikacji uruchamiamy `composer install`, aby dołączyć PSR-4 do projektu. Autoload wygenerowałem przez composera. Dodatkowo w `dev-required` dołączyłem `CodeSniffer`, który pomagał mi w poprawie i sprawdzaniu błędów PSR-1.
Nie dołączałem do projektu automatyzacji dla JS i CSS

# Tryby uruchomieniowe

## POST

Wysyłka formularza na stronie głównej

## CLI

Podpinamy się do głównego pliku `index.php`. Obsługa poleceń w pliku `Boot.php`.

### Parametry

> action

akcja dla polecenia CLI. W naszym wypadku jest tylko 1. `generateCodes`

> parametry

Parametry dostarczane są oddzielnie w zależności od danego polecenia. Akcje definiujemy w metodzie `generateCliRouting`

### Przykład

php index.php --action generateCodes --numberOfCodes 100 --lengthOfCode 10 --file tmp/kody.txt

### generateCodes

#### Parametry

- numberOfCodes
- lengthOfCode
- file

<?php
namespace App\Controllers;

use App;

class IndexController extends App\Controller
{
    public function index($message = null)
    {
        $this->view->message = $this->renderMessageForIndex($message);
        $this->view->render('views/index.phtml');
    }

    private function renderMessageForIndex($message)
    {
        switch ($message) {
        case null:
            return '';
        break;
        case 'fileNotCreated':
            return 'Brak praw do utworzenia pliku';
        break;
        case 'paramsNotCorrect':
            return 'Podane parametry są nieprawidłowe';
        break;
        case 'tooManyCodes':
            return 'Nie można wygenerować podanej ilości kodów dla danego przypadku';
        break;
        default:
            return 'Nieznany błąd';
        break;
        }
    }
}

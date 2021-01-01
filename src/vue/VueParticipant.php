<?php
namespace mywishlist\vue;

class VueParticipant {

    private $tab; // tab array PHP

    public function __construct($tab) {
        $this->tab = $tab;
    }

    private function lesListes() {
        $html = '';
        foreach($this->tab as $liste){
            $html .= "<li>{$liste->titre}</li>";
        }
        return "<ul>$html</ul>";
    }

    private function unItem() {
        var_dump($this->tab);
        $html = '';

    }

    public function render( $select ) {

        switch ($select) {
            case 1 : { // liste des listes
                $content = $this->lesListes();
                break;
            }
            case 3 : { // un item
                $content = $this->unItem();
                break;
            }
        }

        $html = <<<FIN
<!DOCTYPE html>
<html>
  <head>
    <title>Exemple</title>
  </head>
  <body>
		<h1>Wish List</h1>
    $content
  </body>
</html>
FIN;
    }
}
<?php

class sherlock_finder_sherlock
{
    private $objects;

    public function __construct()
    {
        $this->objects = self::buildObjects();
    }

    protected function buildObjects()
    {

        $array = array();

        $a = array();
        $a['name'] = 'Finder';
        $a['text'] = array('Nun ja' => 'ein Finder eben');
        $array[] = $a;

        $a = array();
        $a['name'] = 'Watson';
        $a['text'] = array(
            'Hotkey'    => 'ctrl + space',
            'Limit'     => '10 Treffer',
            'Keywords'  => 'Schränkt die Ergebnisse ein; m = Module, t = Template, u = Benutzer',
            'Keyword+'  => 'Nutze das Keyword gefolgt von einem "+" um einen neuen Datensatz anzulegen. (Bspl: "m+ New Module")'
            );
        $array[] = $a;


        sort($array);
        return json_decode(json_encode($array), FALSE);
    }


    public function getFirstFinderColumn()
    {
        return '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'sherlock'))) . '">' . rex_i18n::msg('b_sherlock_title') . '</span>';
    }


    public function getFinderColumn()
    {
        $rex_var = rex_request('sherlock', 'string');

        $output = '';
        if ($rex_var) {
            // REX_VAR vorhanden
            foreach ($this->objects as $object) {
                if ($rex_var == $object->name) {

                    $text = '';
                    if (isset($object->text) && count($object->text) > 0) {
                        foreach ($object->text as $dt => $dd) {
                            $text .= '<dt>' . $dt. '</dt>';
                            $text .= '<dd>' . $dd . '</dd>';
                        }
                    }

                    $output = '
                    <dl class="sherlock-view">
                        <dt>' . rex_i18n::msg('b_name'). '</dt>
                        <dd>' . $object->name . '</dd>
                        ' . $text . '
                    </dl>';

                    break;
                }
            }
        } else {
            foreach ($this->objects as $object) {
                $output .= '<span data-remote="' . htmlspecialchars_decode(rex_url::backendController(array('sherlock_finder' => 'sherlock', 'sherlock' => $object->name))) . '">' . $object->name . '</span>';
            }
        }

        rex_response::sendContent($output);
        exit();
    }

}

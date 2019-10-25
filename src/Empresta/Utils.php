<?php

namespace App\Empresta;

use Uspdev\Replicado\Pessoa;

class Utils
{

    /** Método que dado um codpes retorna
      * codpes - nome da pessoa
      */
    public static function pessoaUSP($codpes)
    {
        if(Pessoa::dump($codpes)['nompesttd']) {
            $pessoa = explode(',', Pessoa::dump($codpes)['nompesttd'] .','. Pessoa::email($codpes));
            return $pessoa;
        }
        else {
            if( getenv('USAR_TABELA_CRACHA') == 'true') {
                if(Pessoa::cracha($codpes)['nompescra']){
                    $pessoa = explode(',', Pessoa::cracha($codpes)['nompescra'] .', ');
                    return $pessoa;
                }
                else {
                    $pessoa = explode(',', 'Número USP não encontrado nos sistemas USP' .', ');
                    return $pessoa;
                }
            }
            else {
                $pessoa = explode(',', 'Número USP não encontrado nos sistemas USP' .', ');
                return $pessoa;
            }
        }
    }
}

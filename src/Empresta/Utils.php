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
        if(Pessoa::dump($codpes)['nompes']) {
            return Pessoa::dump($codpes)['nompes'] .' '. Pessoa::email($codpes);
        }
        else {
            if( getenv('USAR_TABELA_CRACHA') == 'true') {
                if(Pessoa::cracha($codpes)['nompescra']){
                    return Pessoa::cracha($codpes)['nompescra'];
                }
                else {
                    return 'Número USP não encontrado nos sistemas USP';
                }
            }
            else {
                return 'Número USP não encontrado nos sistemas USP';
            }
        }
    }
}

<?php

/**
 * Trait Helper para tags relacionados ao Código Especificador da Substituição Tributária
 *
 * @category  API
 * @package   NFePHP\NFe\
 * @copyright Copyright (c) 2008-2021
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @author    Gustavo Lidani <lidanig0 at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfe for the canonical source repository
 */

namespace NFePHP\NFe\Make\Det;

use NFePHP\Common\Strings;

trait CEST
{
    /**
     * @var array<\DOMElement>
     */
    protected $aCest = [];

    /**
     * Código Especificador da Substituição Tributária – CEST,
     * que identifica a mercadoria sujeita aos regimes de substituição
     * tributária e de antecipação do recolhimento do imposto.
     * vide NT2015.003 I05C pai
     * tag NFe/infNFe/det[item]/prod/CEST (opcional)
     * NOTA: Ajustado para NT2016_002_v1.30
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagCEST(\stdClass $std)
    {
        $possible = [
            'item', 'CEST', 'indEscala', 'CNPJFab'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I05b <ctrltST> - [item $std->item]:";

        $ctrltST = $this->dom->createElement("ctrltST");

        $this->dom->addChild(
            $ctrltST,
            "CEST",
            Strings::onlyNumbers($std->CEST),
            true,
            "{$identificador} Numero CEST"
        );
        //incluido no layout 4.00
        $this->dom->addChild(
            $ctrltST,
            "indEscala",
            trim($std->indEscala),
            false,
            "{$identificador} Indicador de Produção em escala relevante"
        );
        //incluido no layout 4.00
        $this->dom->addChild(
            $ctrltST,
            "CNPJFab",
            Strings::onlyNumbers($std->CNPJFab),
            false,
            "{$identificador} CNPJ do Fabricante da Mercadoria,"
                . "obrigatório para produto em escala NÃO relevante."
        );
        $this->aCest[$std->item][] = $ctrltST;
        return $ctrltST;
    }
}

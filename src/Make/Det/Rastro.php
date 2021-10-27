<?php

/**
 * Trait Helper para tags relacionados a Rastreabilidade do produto
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

trait Rastro
{
    /**
     * @var array<\DOMElement>
     */
    protected $aRastro = [];

    /**
     * Rastreabilidade do produto podem ser até 500 por item TAG I80 pai I01
     * NOTA: Ajustado para NT2016_002_v1.00
     * tag NFe/infNFe/det[]/prod/rastro
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagRastro(\stdClass $std)
    {
        $possible = [
            'item',
            'nLote',
            'qLote',
            'dFab',
            'dVal',
            'cAgreg'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I80 <rastro> - [item $std->item]:";

        $rastro = $this->dom->createElement('rastro');

        $this->dom->addChild(
            $rastro,
            "nLote",
            substr(trim($std->nLote), 0, 20),
            true,
            "{$identificador} Número do lote"
        );
        $this->dom->addChild(
            $rastro,
            "qLote",
            $this->conditionalNumberFormatting($std->qLote, 3),
            true,
            "{$identificador} Quantidade do lote"
        );
        $this->dom->addChild(
            $rastro,
            "dFab",
            trim($std->dFab),
            true,
            "{$identificador} Data de fabricação"
        );
        $this->dom->addChild(
            $rastro,
            "dVal",
            trim($std->dVal),
            true,
            "{$identificador} Data da validade"
        );
        $this->dom->addChild(
            $rastro,
            "cAgreg",
            $std->cAgreg,
            false,
            "{$identificador} Código de Agregação"
        );
        $this->aRastro[$std->item][] = $rastro;
        return $rastro;
    }
}

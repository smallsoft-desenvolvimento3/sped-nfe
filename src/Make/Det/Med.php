<?php

/**
 * Trait Helper para tags relacionados a Detalhamento de medicamentos
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

trait Med
{
    /**
     * @var array<\DOMElement>
     */
    protected $aMed = [];

    /**
     * Detalhamento de medicamentos K01 pai I90
     * NOTA: Ajustado para NT2018.005
     * tag NFe/infNFe/det[]/prod/med (opcional)
     * @param stdClass $std
     * @return DOMElement
     */
    public function tagmed(\stdClass $std)
    {
        $possible = [
            'item',
            'vPMC',
            'cProdANVISA',
            'xMotivoIsencao'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "K01 <med> - [item $std->item]:";

        $med = $this->dom->createElement('med');

        $this->dom->addChild(
            $med,
            "cProdANVISA",
            $std->cProdANVISA,
            false,
            "{$identificador} Numero ANVISA"
        );
        $this->dom->addChild(
            $med,
            "xMotivoIsencao",
            $std->xMotivoIsencao,
            false,
            "{$identificador} Motivo da isenção da ANVISA"
        );
        $this->dom->addChild(
            $med,
            "vPMC",
            $this->conditionalNumberFormatting($std->vPMC),
            true,
            "{$identificador} Preço máximo consumidor"
        );
        $this->aMed[$std->item] = $med;
        return $med;
    }
}

<?php

/**
 * Trait Helper para tags relacionados a retenções de tributos
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

namespace NFePHP\NFe\Make\Total;

trait RetTrib
{
    /**
     * @var \DOMElement
     */
    protected $retTrib;

    /**
     * Grupo Retenções de Tributos W23 pai W01
     * tag NFe/infNFe/total/reTrib (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagretTrib(\stdClass $std)
    {
        $possible = [
            'vRetPIS',
            'vRetCOFINS',
            'vRetCSLL',
            'vBCIRRF',
            'vIRRF',
            'vBCRetPrev',
            'vRetPrev'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $retTrib = $this->dom->createElement('retTrib');

        $this->dom->addChild(
            $retTrib,
            "vRetPIS",
            $this->conditionalNumberFormatting($std->vRetPIS),
            false,
            "Valor Retido de PIS"
        );
        $this->dom->addChild(
            $retTrib,
            "vRetCOFINS",
            $this->conditionalNumberFormatting($std->vRetCOFINS),
            false,
            "Valor Retido de COFINS"
        );
        $this->dom->addChild(
            $retTrib,
            "vRetCSLL",
            $this->conditionalNumberFormatting($std->vRetCSLL),
            false,
            "Valor Retido de CSLL"
        );
        $this->dom->addChild(
            $retTrib,
            "vBCIRRF",
            $this->conditionalNumberFormatting($std->vBCIRRF),
            false,
            "Base de Cálculo do IRRF"
        );
        $this->dom->addChild(
            $retTrib,
            "vIRRF",
            $this->conditionalNumberFormatting($std->vIRRF),
            false,
            "Valor Retido do IRRF"
        );
        $this->dom->addChild(
            $retTrib,
            "vBCRetPrev",
            $this->conditionalNumberFormatting($std->vBCRetPrev),
            false,
            "Base de Cálculo da Retenção da Previdência Social"
        );
        $this->dom->addChild(
            $retTrib,
            "vRetPrev",
            $this->conditionalNumberFormatting($std->vRetPrev),
            false,
            "Valor da Retenção da Previdência Social"
        );
        $this->retTrib = $retTrib;
        return $retTrib;
    }
}

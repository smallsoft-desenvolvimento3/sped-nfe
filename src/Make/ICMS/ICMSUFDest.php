<?php

/**
 * Trait Helper para impostos relacionados a ICMS UF Dest
 *
 * Essa trait é dependente da \NFePHP\NFe\Make\ICMS\ICMS
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

namespace NFePHP\NFe\Make\ICMS;

trait ICMSUFDest
{
    /**
     * @var array<\DOMElement>
     */
    protected $aICMSUFDest = [];

    /**
     * Grupo ICMSUFDest NA01 pai M01
     * tag NFe/infNFe/det[]/imposto/ICMSUFDest (opcional)
     * Grupo a ser informado nas vendas interestaduais para consumidor final,
     * não contribuinte do ICMS
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagICMSUFDest(\stdClass $std)
    {
        $possible = [
            'item',
            'vBCUFDest',
            'vBCFCPUFDest',
            'pFCPUFDest',
            'pICMSUFDest',
            'pICMSInter',
            'pICMSInterPart',
            'vFCPUFDest',
            'vICMSUFDest',
            'vICMSUFRemet'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $this->stdTot->vICMSUFDest += (float) $std->vICMSUFDest;
        $this->stdTot->vFCPUFDest += (float) $std->vFCPUFDest;
        $this->stdTot->vICMSUFRemet += (float) $std->vICMSUFRemet;

        $icmsUFDest = $this->dom->createElement('ICMSUFDest');

        $this->dom->addChild(
            $icmsUFDest,
            "vBCUFDest",
            $this->conditionalNumberFormatting($std->vBCUFDest),
            true,
            "[item $std->item] Valor da BC do ICMS na UF do destinatário"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "vBCFCPUFDest",
            $this->conditionalNumberFormatting($std->vBCFCPUFDest),
            false,
            "[item $std->item] Valor da BC do ICMS na UF do destinatário"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "pFCPUFDest",
            $this->conditionalNumberFormatting($std->pFCPUFDest, 4),
            false,
            "[item $std->item] Percentual do ICMS relativo ao Fundo de Combate à Pobreza (FCP) na UF de destino"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "pICMSUFDest",
            $this->conditionalNumberFormatting($std->pICMSUFDest, 4),
            true,
            "[item $std->item] Alíquota interna da UF do destinatário"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "pICMSInter",
            $this->conditionalNumberFormatting($std->pICMSInter, 2),
            true,
            "[item $std->item] Alíquota interestadual das UF envolvidas"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "pICMSInterPart",
            $this->conditionalNumberFormatting($std->pICMSInterPart, 4),
            true,
            "[item $std->item] Percentual provisório de partilha entre os Estados"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "vFCPUFDest",
            $this->conditionalNumberFormatting($std->vFCPUFDest),
            false,
            "[item $std->item] Valor do ICMS relativo ao Fundo de Combate à Pobreza (FCP) da UF de destino"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "vICMSUFDest",
            $this->conditionalNumberFormatting($std->vICMSUFDest),
            true,
            "[item $std->item] Valor do ICMS de partilha para a UF do destinatário"
        );
        $this->dom->addChild(
            $icmsUFDest,
            "vICMSUFRemet",
            $this->conditionalNumberFormatting($std->vICMSUFRemet),
            true,
            "[item $std->item] Valor do ICMS de partilha para a UF do remetente"
        );
        $this->aICMSUFDest[$std->item] = $icmsUFDest;
        return $icmsUFDest;
    }
}

<?php

/**
 * Trait Helper para tags relacionados a retenção de ICMS transporte
 *
 * Essa trait depende da \NFePHP\NFe\Make\Transporte\Transp
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

namespace NFePHP\NFe\Make\Transporte;

trait RetTransp
{
    /**
     * Grupo Retenção ICMS transporte X11 pai X01
     * tag NFe/infNFe/transp/retTransp (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagretTransp(\stdClass $std)
    {
        $possible = [
            'vServ',
            'vBCRet',
            'pICMSRet',
            'vICMSRet',
            'CFOP',
            'cMunFG'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $retTransp = $this->dom->createElement("retTransp");
        $this->dom->addChild(
            $retTransp,
            "vServ",
            $this->conditionalNumberFormatting($std->vServ),
            true,
            "Valor do Serviço"
        );
        $this->dom->addChild(
            $retTransp,
            "vBCRet",
            $this->conditionalNumberFormatting($std->vBCRet),
            true,
            "BC da Retenção do ICMS"
        );
        $this->dom->addChild(
            $retTransp,
            "pICMSRet",
            $this->conditionalNumberFormatting($std->pICMSRet, 4),
            true,
            "Alíquota da Retenção"
        );
        $this->dom->addChild(
            $retTransp,
            "vICMSRet",
            $this->conditionalNumberFormatting($std->vICMSRet),
            true,
            "Valor do ICMS Retido"
        );
        $this->dom->addChild(
            $retTransp,
            "CFOP",
            $std->CFOP,
            true,
            "CFOP"
        );
        $this->dom->addChild(
            $retTransp,
            "cMunFG",
            $std->cMunFG,
            true,
            "Código do município de ocorrência do fato gerador do ICMS do transporte"
        );
        $this->dom->appChild(
            $this->transp,
            $retTransp,
            'A tag transp deveria ter sido carregada primeiro.'
        );
        return $retTransp;
    }
}

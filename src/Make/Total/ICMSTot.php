<?php

/**
 * Trait Helper para impostos relacionados aos totais de ICMS
 * Esta trait basica está estruturada para montar a tag de total de ICMS para o
 * layout versão 4.00, os demais modelos serão derivados deste
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

trait ICMSTot
{
    /**
     * @var \DOMElement
     */
    protected $ICMSTot;

    /**
     * @var \stdClass
     */
    protected $stdICMSTot;

    /**
     * Grupo Totais referentes ao ICMS W02 pai W01
     * tag NFe/infNFe/total/ICMSTot
     * @param \stdClass|null $std
     * @return \DOMElement
     */
    public function tagICMSTot(\stdClass $std = null)
    {
        $this->buildICMSTot();

        $possible = [
            'vBC',
            'vICMS',
            'vICMSDeson',
            'vBCST',
            'vST',
            'vProd',
            'vFrete',
            'vSeg',
            'vDesc',
            'vII',
            'vIPI',
            'vPIS',
            'vCOFINS',
            'vOutro',
            'vNF',
            'vIPIDevol',
            'vTotTrib',
            'vFCP',
            'vFCPST',
            'vFCPSTRet',
            'vFCPUFDest',
            'vICMSUFDest',
            'vICMSUFRemet',
        ];
        if (isset($std)) {
            $std = $this->equilizeParameters($std, $possible);
        }
        $this->stdICMSTot = $std;

        $vBC = isset($std->vBC) ? $std->vBC : $this->stdTot->vBC;
        $vICMS = isset($std->vICMS) ? $std->vICMS : $this->stdTot->vICMS;
        $vICMSDeson = !empty($std->vICMSDeson) ? $std->vICMSDeson : $this->stdTot->vICMSDeson;
        $vBCST = !empty($std->vBCST) ? $std->vBCST : $this->stdTot->vBCST;
        $vST = !empty($std->vST) ? $std->vST : $this->stdTot->vST;
        $vProd = !empty($std->vProd) ? $std->vProd : $this->stdTot->vProd;
        $vFrete = !empty($std->vFrete) ? $std->vFrete : $this->stdTot->vFrete;
        $vSeg = !empty($std->vSeg) ? $std->vSeg : $this->stdTot->vSeg;
        $vDesc = !empty($std->vDesc) ? $std->vDesc : $this->stdTot->vDesc;
        $vII = !empty($std->vII) ? $std->vII : $this->stdTot->vII;
        $vIPI = !empty($std->vIPI) ? $std->vIPI : $this->stdTot->vIPI;
        $vPIS = !empty($std->vPIS) ? $std->vPIS : $this->stdTot->vPIS;
        $vCOFINS = !empty($std->vCOFINS) ? $std->vCOFINS : $this->stdTot->vCOFINS;
        $vOutro = !empty($std->vOutro) ? $std->vOutro : $this->stdTot->vOutro;
        $vNF = !empty($std->vNF) ? $std->vNF : $this->stdTot->vNF;
        $vIPIDevol = !empty($std->vIPIDevol) ? $std->vIPIDevol : $this->stdTot->vIPIDevol;
        $vTotTrib = !empty($std->vTotTrib) ? $std->vTotTrib : $this->stdTot->vTotTrib;
        $vFCP = !empty($std->vFCP) ? $std->vFCP : $this->stdTot->vFCP;
        $vFCPST = !empty($std->vFCPST) ? $std->vFCPST : $this->stdTot->vFCPST;
        $vFCPSTRet = !empty($std->vFCPSTRet) ? $std->vFCPSTRet : $this->stdTot->vFCPSTRet;
        $vFCPUFDest = !empty($std->vFCPUFDest) ? $std->vFCPUFDest : $this->stdTot->vFCPUFDest;
        $vICMSUFDest = !empty($std->vICMSUFDest) ? $std->vICMSUFDest : $this->stdTot->vICMSUFDest;
        $vICMSUFRemet = !empty($std->vICMSUFRemet) ? $std->vICMSUFRemet : $this->stdTot->vICMSUFRemet;

        //campos opcionais incluir se maior que zero
        $vFCPUFDest = ($vFCPUFDest > 0) ? number_format($vFCPUFDest, 2, '.', '') : null;
        $vICMSUFDest = ($vICMSUFDest > 0) ? number_format($vICMSUFDest, 2, '.', '') : null;
        $vICMSUFRemet = ($vICMSUFRemet > 0) ? number_format($vICMSUFRemet, 2, '.', '') : null;
        $vTotTrib = ($vTotTrib > 0) ? number_format($vTotTrib, 2, '.', '') : null;

        //campos obrigatórios para 4.00
        $vFCP = number_format($vFCP, 2, '.', '');
        $vFCPST = number_format($vFCPST, 2, '.', '');
        $vFCPSTRet = number_format($vFCPSTRet, 2, '.', '');
        $vIPIDevol = number_format($vIPIDevol, 2, '.', '');

        $ICMSTot = $this->dom->createElement("ICMSTot");
        $this->dom->addChild(
            $ICMSTot,
            "vBC",
            $this->conditionalNumberFormatting($vBC),
            true,
            "Base de Cálculo do ICMS"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vICMS",
            $this->conditionalNumberFormatting($vICMS),
            true,
            "Valor Total do ICMS"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vICMSDeson",
            $this->conditionalNumberFormatting($vICMSDeson),
            true,
            "Valor Total do ICMS desonerado"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vFCPUFDest",
            $this->conditionalNumberFormatting($vFCPUFDest),
            false,
            "Valor total do ICMS relativo ao Fundo de Combate à Pobreza(FCP) "
                . "para a UF de destino"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vICMSUFDest",
            $this->conditionalNumberFormatting($vICMSUFDest),
            false,
            "Valor total do ICMS de partilha para a UF do destinatário"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vICMSUFRemet",
            $this->conditionalNumberFormatting($vICMSUFRemet),
            false,
            "Valor total do ICMS de partilha para a UF do remetente"
        );
        //incluso no layout 4.00
        $this->dom->addChild(
            $ICMSTot,
            "vFCP",
            $this->conditionalNumberFormatting($vFCP),
            false,
            "Valor total do ICMS relativo ao Fundo de Combate à Pobreza(FCP) "
                . "para a UF de destino"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vBCST",
            $this->conditionalNumberFormatting($vBCST),
            true,
            "Base de Cálculo do ICMS ST"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vST",
            $this->conditionalNumberFormatting($vST),
            true,
            "Valor Total do ICMS ST"
        );
        //incluso na 4.00
        $this->dom->addChild(
            $ICMSTot,
            "vFCPST",
            $this->conditionalNumberFormatting($vFCPST),
            false, //true para 4.00
            "Valor Total do FCP (Fundo de Combate à Pobreza) "
                . "retido por substituição tributária"
        );
        //incluso na 4.00
        $this->dom->addChild(
            $ICMSTot,
            "vFCPSTRet",
            $this->conditionalNumberFormatting($vFCPSTRet),
            false, //true para 4.00
            "Valor Total do FCP retido anteriormente por "
                . "Substituição Tributária"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vProd",
            $this->conditionalNumberFormatting($vProd),
            true,
            "Valor Total dos produtos e serviços"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vFrete",
            $this->conditionalNumberFormatting($vFrete),
            true,
            "Valor Total do Frete"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vSeg",
            $this->conditionalNumberFormatting($vSeg),
            true,
            "Valor Total do Seguro"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vDesc",
            $this->conditionalNumberFormatting($vDesc),
            true,
            "Valor Total do Desconto"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vII",
            $this->conditionalNumberFormatting($vII),
            true,
            "Valor Total do II"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vIPI",
            $this->conditionalNumberFormatting($vIPI),
            true,
            "Valor Total do IPI"
        );
        //incluso 4.00
        $this->dom->addChild(
            $ICMSTot,
            "vIPIDevol",
            $this->conditionalNumberFormatting($vIPIDevol),
            false,
            "Valor Total do IPI"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vPIS",
            $this->conditionalNumberFormatting($vPIS),
            true,
            "Valor do PIS"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vCOFINS",
            $this->conditionalNumberFormatting($vCOFINS),
            true,
            "Valor da COFINS"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vOutro",
            $this->conditionalNumberFormatting($vOutro),
            true,
            "Outras Despesas acessórias"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vNF",
            $this->conditionalNumberFormatting($vNF),
            true,
            "Valor Total da NF-e"
        );
        $this->dom->addChild(
            $ICMSTot,
            "vTotTrib",
            $this->conditionalNumberFormatting($vTotTrib),
            false,
            "Valor aproximado total de tributos federais, estaduais e municipais."
        );
        return $this->ICMSTot = $ICMSTot;
    }

    /**
     * Grupo Totais da NF-e W01 pai A01
     * tag NFe/infNFe/total
     */
    protected function buildICMSTot()
    {
        //round all values
        $this->stdTot->vBC = round($this->stdTot->vBC, 2);
        $this->stdTot->vICMS = round($this->stdTot->vICMS, 2);
        $this->stdTot->vICMSDeson = round($this->stdTot->vICMSDeson, 2);
        $this->stdTot->vFCP = round($this->stdTot->vFCP, 2);
        $this->stdTot->vFCPUFDest = round($this->stdTot->vFCPUFDest, 2);
        $this->stdTot->vICMSUFDest = round($this->stdTot->vICMSUFDest, 2);
        $this->stdTot->vICMSUFRemet = round($this->stdTot->vICMSUFRemet, 2);
        $this->stdTot->vBCST = round($this->stdTot->vBCST, 2);
        $this->stdTot->vST = round($this->stdTot->vST, 2);
        $this->stdTot->vFCPST = round($this->stdTot->vFCPST, 2);
        $this->stdTot->vFCPSTRet = round($this->stdTot->vFCPSTRet, 2);
        $this->stdTot->vProd = round($this->stdTot->vProd, 2);
        $this->stdTot->vFrete = round($this->stdTot->vFrete, 2);
        $this->stdTot->vSeg = round($this->stdTot->vSeg, 2);
        $this->stdTot->vDesc = round($this->stdTot->vDesc, 2);
        $this->stdTot->vII = round($this->stdTot->vII, 2);
        $this->stdTot->vIPI = round($this->stdTot->vIPI, 2);
        $this->stdTot->vIPIDevol = round($this->stdTot->vIPIDevol, 2);
        $this->stdTot->vPIS = round($this->stdTot->vPIS, 2);
        $this->stdTot->vCOFINS = round($this->stdTot->vCOFINS, 2);
        $this->stdTot->vOutro = round($this->stdTot->vOutro, 2);
        $this->stdTot->vNF = round($this->stdTot->vNF, 2);
        $this->stdTot->vTotTrib = round($this->stdTot->vTotTrib, 2);

        $this->stdTot->vNF = $this->stdTot->vProd
            - $this->stdTot->vDesc
            - $this->stdTot->vICMSDeson
            + $this->stdTot->vST
            + $this->stdTot->vFCPST
            + $this->stdTot->vFrete
            + $this->stdTot->vSeg
            + $this->stdTot->vOutro
            + $this->stdTot->vII
            + $this->stdTot->vIPI
            + $this->stdTot->vIPIDevol
            + $this->stdISSQNTot->vServ
            + $this->stdTot->vPISST
            + $this->stdTot->vCOFINSST;
    }
}

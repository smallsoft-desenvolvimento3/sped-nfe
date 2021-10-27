<?php

/**
 * Trait Helper para impostos relacionados a ICMS do Simples Nacional
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

trait ICMSSN
{
    /**
     * Tributação ICMS pelo Simples Nacional N10c pai N01
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMSSN N10c pai N01
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagICMSSN(\stdClass $std)
    {
        $possible = [
            'item',
            'orig',
            'CSOSN',
            'pCredSN',
            'vCredICMSSN',
            'modBCST',
            'pMVAST',
            'pRedBCST',
            'vBCST',
            'pICMSST',
            'vICMSST',
            'vBCFCPST',
            'pFCPST',
            'vFCPST',
            'vBCSTRet',
            'pST',
            'vICMSSTRet',
            'vBCFCPSTRet',
            'pFCPSTRet',
            'vFCPSTRet',
            'modBC',
            'vBC',
            'pRedBC',
            'pICMS',
            'vICMS',
            'pRedBCEfet',
            'vBCEfet',
            'pICMSEfet',
            'vICMSEfet',
            'vICMSSubstituto'
        ];
        $std = $this->equilizeParameters($std, $possible);
        //totalizador generico
        $this->stdTot->vFCPST += (float) !empty($std->vFCPST) ? $std->vFCPST : 0;
        $this->stdTot->vFCPSTRet += (float) !empty($std->vFCPSTRet) ? $std->vFCPSTRet : 0;
        switch ($std->CSOSN) {
            case '101':
                $icmsSN = $this->dom->createElement("ICMSSN101");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pCredSN',
                    $this->conditionalNumberFormatting($std->pCredSN, 2),
                    true,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $this->conditionalNumberFormatting($std->vCredICMSSN),
                    true,
                    "[item $std->item] Valor crédito do ICMS que pode ser aproveitado nos termos do"
                        . " art. 23 da LC 123 (Simples Nacional)"
                );
                break;
            case '102':
            case '103':
            case '300':
            case '400':
                $icmsSN = $this->dom->createElement("ICMSSN102");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                break;
            case '201':
                $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
                $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

                $icmsSN = $this->dom->createElement("ICMSSN201");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'modBCST',
                    $std->modBCST,
                    true,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pMVAST',
                    $this->conditionalNumberFormatting($std->pMVAST, 4),
                    false,
                    "[item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pRedBCST',
                    $this->conditionalNumberFormatting($std->pRedBCST, 4),
                    false,
                    "[item $std->item] Percentual da Redução de BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCST',
                    $this->conditionalNumberFormatting($std->vBCST),
                    true,
                    "[item $std->item] Valor da BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pICMSST',
                    $this->conditionalNumberFormatting($std->pICMSST, 4),
                    true,
                    "[item $std->item] Alíquota do imposto do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSST',
                    $this->conditionalNumberFormatting($std->vICMSST),
                    true,
                    "[item $std->item] Valor do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCFCPST',
                    $this->conditionalNumberFormatting($std->vBCFCPST),
                    isset($std->vBCFCPST) ? true : false,
                    "[item $std->item] Valor da Base de Cálculo do FCP "
                        . "retido por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pFCPST',
                    $this->conditionalNumberFormatting($std->pFCPST, 4),
                    isset($std->pFCPST) ? true : false,
                    "[item $std->item] Percentual do FCP retido por "
                        . "Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vFCPST',
                    $this->conditionalNumberFormatting($std->vFCPST),
                    isset($std->vFCPST) ? true : false,
                    "[item $std->item] Valor do FCP retido por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pCredSN',
                    $this->conditionalNumberFormatting($std->pCredSN, 4),
                    false,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $this->conditionalNumberFormatting($std->vCredICMSSN),
                    false,
                    "[item $std->item] Valor crédito do ICMS que pode ser aproveitado nos "
                        . "termos do art. 23 da LC 123 (Simples Nacional)"
                );
                break;
            case '202':
            case '203':
                $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
                $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

                $icmsSN = $this->dom->createElement("ICMSSN202");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'modBCST',
                    $std->modBCST,
                    true,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pMVAST',
                    $this->conditionalNumberFormatting($std->pMVAST, 4),
                    false,
                    "[item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pRedBCST',
                    $this->conditionalNumberFormatting($std->pRedBCST, 4),
                    false,
                    "[item $std->item] Percentual da Redução de BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCST',
                    $this->conditionalNumberFormatting($std->vBCST),
                    true,
                    "[item $std->item] Valor da BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pICMSST',
                    $this->conditionalNumberFormatting($std->pICMSST, 4),
                    true,
                    "[item $std->item] Alíquota do imposto do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSST',
                    $this->conditionalNumberFormatting($std->vICMSST),
                    true,
                    "[item $std->item] Valor do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCFCPST',
                    $this->conditionalNumberFormatting($std->vBCFCPST),
                    isset($std->vBCFCPST) ? true : false,
                    "[item $std->item] Valor da Base de Cálculo do FCP "
                        . "retido por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pFCPST',
                    $this->conditionalNumberFormatting($std->pFCPST, 4),
                    isset($std->pFCPST) ? true : false,
                    "[item $std->item] Percentual do FCP retido por "
                        . "Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vFCPST',
                    $this->conditionalNumberFormatting($std->vFCPST),
                    isset($std->vFCPST) ? true : false,
                    "[item $std->item] Valor do FCP retido por Substituição Tributária"
                );
                break;
            case '500':
                $icmsSN = $this->dom->createElement("ICMSSN500");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCSTRet',
                    $this->conditionalNumberFormatting($std->vBCSTRet),
                    isset($std->vBCSTRet) ? true : false,
                    "[item $std->item] Valor da BC do ICMS ST retido"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pST',
                    $this->conditionalNumberFormatting($std->pST, 4),
                    isset($std->pST) ? true : false,
                    "[item $std->item] Alíquota suportada pelo Consumidor Final"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSSubstituto',
                    $this->conditionalNumberFormatting($std->vICMSSubstituto),
                    false,
                    "[item $std->item] Valor do ICMS próprio do Substituto"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSSTRet',
                    $this->conditionalNumberFormatting($std->vICMSSTRet),
                    isset($std->vICMSSTRet) ? true : false,
                    "[item $std->item] Valor do ICMS ST retido"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCFCPSTRet',
                    $this->conditionalNumberFormatting($std->vBCFCPSTRet, 2),
                    isset($std->vBCFCPSTRet) ? true : false,
                    "[item $std->item] Valor da Base de Cálculo do FCP "
                        . "retido anteriormente por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pFCPSTRet',
                    $this->conditionalNumberFormatting($std->pFCPSTRet, 4),
                    isset($std->pFCPSTRet) ? true : false,
                    "[item $std->item] Percentual do FCP retido anteriormente por "
                        . "Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vFCPSTRet',
                    $this->conditionalNumberFormatting($std->vFCPSTRet),
                    isset($std->vFCPSTRet) ? true : false,
                    "[item $std->item] Valor do FCP retido anteiormente por "
                        . "Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pRedBCEfet',
                    $this->conditionalNumberFormatting($std->pRedBCEfet, 4),
                    isset($std->pRedBCEfet) ? true : false,
                    "[item $std->item] Percentual de redução da base "
                        . "de cálculo efetiva"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCEfet',
                    $this->conditionalNumberFormatting($std->vBCEfet),
                    isset($std->vBCEfet) ? true : false,
                    "[item $std->item] Valor da base de cálculo efetiva"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pICMSEfet',
                    $this->conditionalNumberFormatting($std->pICMSEfet, 4),
                    isset($std->pICMSEfet) ? true : false,
                    "[item $std->item] Alíquota do ICMS efetiva"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSEfet',
                    $this->conditionalNumberFormatting($std->vICMSEfet),
                    isset($std->vICMSEfet) ? true : false,
                    "[item $std->item] Valor do ICMS efetivo"
                );
                break;
            case '900':
                $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
                $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;
                $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
                $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;
                $icmsSN = $this->dom->createElement("ICMSSN900");
                $this->dom->addChild(
                    $icmsSN,
                    'orig',
                    $std->orig,
                    true,
                    "[item $std->item] Origem da mercadoria"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'CSOSN',
                    $std->CSOSN,
                    true,
                    "[item $std->item] Código de Situação da Operação Simples Nacional"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'modBC',
                    $std->modBC,
                    isset($std->modBC) ? true : false,
                    "[item $std->item] Modalidade de determinação da BC do ICMS"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBC',
                    $this->conditionalNumberFormatting($std->vBC),
                    isset($std->vBC) ? true : false,
                    "[item $std->item] Valor da BC do ICMS"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pRedBC',
                    $this->conditionalNumberFormatting($std->pRedBC, 4),
                    false,
                    "[item $std->item] Percentual da Redução de BC"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pICMS',
                    $this->conditionalNumberFormatting($std->pICMS, 4),
                    isset($std->pICMS) ? true : false,
                    "[item $std->item] Alíquota do imposto"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMS',
                    $this->conditionalNumberFormatting($std->vICMS),
                    isset($std->pICMS) ? true : false,
                    "[item $std->item] Valor do ICMS"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'modBCST',
                    $std->modBCST,
                    isset($std->modBCST) ? true : false,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pMVAST',
                    $this->conditionalNumberFormatting($std->pMVAST, 4),
                    false,
                    "[item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pRedBCST',
                    $this->conditionalNumberFormatting($std->pRedBCST, 4),
                    false,
                    "[item $std->item] Percentual da Redução de BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCST',
                    $this->conditionalNumberFormatting($std->vBCST),
                    isset($std->vBCST) ? true : false,
                    "[item $std->item] Valor da BC do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pICMSST',
                    $this->conditionalNumberFormatting($std->pICMSST, 4),
                    isset($std->pICMSST) ? true : false,
                    "[item $std->item] Alíquota do imposto do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vICMSST',
                    $this->conditionalNumberFormatting($std->vICMSST),
                    isset($std->vICMSST) ? true : false,
                    "[item $std->item] Valor do ICMS ST"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vBCFCPST',
                    $this->conditionalNumberFormatting($std->vBCFCPST),
                    isset($std->vBCFCPST) ? true : false,
                    "[item $std->item] Valor da Base de Cálculo do FCP "
                        . "retido por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pFCPST',
                    $this->conditionalNumberFormatting($std->pFCPST, 4),
                    isset($std->pFCPST) ? true : false,
                    "[item $std->item] Percentual do FCP retido por "
                        . "Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vFCPST',
                    $this->conditionalNumberFormatting($std->vFCPST),
                    isset($std->vFCPST) ? true : false,
                    "[item $std->item] Valor do FCP retido por Substituição Tributária"
                );
                $this->dom->addChild(
                    $icmsSN,
                    'pCredSN',
                    $this->conditionalNumberFormatting($std->pCredSN, 4),
                    isset($std->pCredSN) ? true : false,
                    "[item $std->item] Alíquota aplicável de cálculo do crédito (Simples Nacional)."
                );
                $this->dom->addChild(
                    $icmsSN,
                    'vCredICMSSN',
                    $this->conditionalNumberFormatting($std->vCredICMSSN),
                    isset($std->vCredICMSSN) ? true : false,
                    "[item $std->item] Valor crédito do ICMS que pode ser aproveitado nos termos do"
                        . " art. 23 da LC 123 (Simples Nacional)"
                );
                break;
        }
        //caso exista a tag aICMS[$std-item] inserir nela caso contrario criar
        if (!empty($this->aICMS[$std->item])) {
            $tagIcms = $this->aICMS[$std->item];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }
        if (isset($icmsSN)) {
            $this->dom->appChild($tagIcms, $icmsSN, "Inserindo ICMSST em ICMS[$std->item]");
        }
        $this->aICMS[$std->item] = $tagIcms;
        return $tagIcms;
    }
}

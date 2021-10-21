<?php

/**
 * Trait Helper para impostos relacionados a ICMS
 * Esta trait basica está estruturada para montar as tags de ICMS para o
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

namespace NFePHP\NFe\Make\ICMS;

trait ICMS
{

    /**
     * @var array<\DOMElement>
     */
    protected $aICMS = [];

    /**
     * Informações do ICMS da Operação própria e ST N01 pai M01
     * tag NFe/infNFe/det[]/imposto/ICMS
     * NOTA: ajustado NT 2020.005-v1.20
     * @param \stdClass $std
     * @return \DOMElement|void
     */
    public function tagICMS(\stdClass $std)
    {
        $possible = [
            'item',
            'orig',
            'CST',
            'modBC',
            'vBC',
            'pICMS',
            'vICMS',
            'pFCP',
            'vFCP',
            'vBCFCP',
            'modBCST',
            'pMVAST',
            'pRedBCST',
            'vBCST',
            'pICMSST',
            'vICMSST',
            'vBCFCPST',
            'pFCPST',
            'vFCPST',
            'vICMSDeson',
            'motDesICMS',
            'pRedBC',
            'vICMSOp',
            'pDif',
            'vICMSDif',
            'vBCSTRet',
            'pST',
            'vICMSSTRet',
            'vBCFCPSTRet',
            'pFCPSTRet',
            'vFCPSTRet',
            'pRedBCEfet',
            'vBCEfet',
            'pICMSEfet',
            'vICMSEfet',
            'vICMSSubstituto',
            'vICMSSTDeson',
            'motDesICMSST',
            'pFCPDif',
            'vFCPDif',
            'vFCPEfet',
        ];

        $std = $this->equilizeParameters($std, $possible);

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'N01 <ICMSxx> -';

        // totalização generica
        $this->stdTot->vICMSDeson += (float) !empty($std->vICMSDeson) ? $std->vICMSDeson : 0;
        $this->stdTot->vFCP += (float) !empty($std->vFCP) ? $std->vFCP : 0;
        $this->stdTot->vFCPST += (float) !empty($std->vFCPST) ? $std->vFCPST : 0;
        $this->stdTot->vFCPSTRet += (float) !empty($std->vFCPSTRet) ? $std->vFCPSTRet : 0;

        switch ($std->CST) {
            case '00':
                $icms = $this->buildICMS00($std, $identificador);
                break;
            case '10':
                $icms = $this->buildICMS10($std, $identificador);
                break;
            case '20':
                $icms = $this->buildICMS20($std, $identificador);
                break;
            case '30':
                $icms = $this->buildICMS30($std, $identificador);
                break;
            case '40':
            case '41':
            case '50':
                $icms = $this->buildICMS40($std, $identificador);
                break;
            case '51':
                $icms = $this->buildICMS51($std, $identificador);
                break;
            case '60':
                $icms = $this->buildICMS60($std, $identificador);
                break;
            case '70':
                $icms = $this->buildICMS70($std, $identificador);
                break;
            case '90':
                $icms = $this->buildICMS90($std, $identificador);
                break;
        }

        $tagIcms = $this->dom->createElement('ICMS');

        if (isset($icms)) {
            $tagIcms->appendChild($icms);
        }

        $this->aICMS[$std->item] = $tagIcms;

        return $tagIcms;
    }

    /**
     * Função que cria a tag ICMS00
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS00
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS00(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;

        $icms = $this->dom->createElement("ICMS00");

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 00"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            true,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $this->conditionalNumberFormatting($std->vFCP),
            false,
            "{$identificador} [item $std->item] Valor do Fundo de Combate "
                . "à Pobreza (FCP)"
        );

        // retorna a tag
        return $icms;
    }

    /**
     * Função que cria a tag ICMS10
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS10
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS10(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;
        $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
        $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

        $icms = $this->dom->createElement("ICMS10");

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 10"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            true,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCP',
            $this->conditionalNumberFormatting($std->vBCFCP),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $this->conditionalNumberFormatting($std->vFCP),
            false,
            "{$identificador} [item $std->item] Valor do FCP"
        );
        $this->dom->addChild(
            $icms,
            'modBCST',
            $std->modBCST,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pMVAST',
            $this->conditionalNumberFormatting($std->pMVAST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pRedBCST',
            $this->conditionalNumberFormatting($std->pRedBCST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCST',
            $this->conditionalNumberFormatting($std->vBCST),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pICMSST',
            $this->conditionalNumberFormatting($std->pICMSST, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSST',
            $this->conditionalNumberFormatting($std->vICMSST),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCPST',
            $this->conditionalNumberFormatting($std->vBCFCPST),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'pFCPST',
            $this->conditionalNumberFormatting($std->pFCPST, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP) ST"
        );
        $this->dom->addChild(
            $icms,
            'vFCPST',
            $this->conditionalNumberFormatting($std->vFCPST),
            false,
            "{$identificador} [item $std->item] Valor do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSSTDeson',
            $this->conditionalNumberFormatting($std->vICMSSTDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS- ST desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMSST',
            $std->motDesICMSST ?? null,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS- ST"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS20
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS20
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS20(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;

        $icms = $this->dom->createElement('ICMS20');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 20"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pRedBC',
            $this->conditionalNumberFormatting($std->pRedBC, 4),
            true,
            "{$identificador} [item $std->item] Percentual da Redução de BC"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            true,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCP',
            $this->conditionalNumberFormatting($std->vBCFCP),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $this->conditionalNumberFormatting($std->vFCP),
            false,
            "{$identificador} [item $std->item] Valor do FCP"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDeson',
            $this->conditionalNumberFormatting($std->vICMSDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMS',
            $std->motDesICMS,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS30
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS30
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS30(\stdClass $std, $identificador)
    {
        $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
        $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

        $icms = $this->dom->createElement('ICMS30');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 30"
        );
        $this->dom->addChild(
            $icms,
            'modBCST',
            $std->modBCST,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pMVAST',
            $this->conditionalNumberFormatting($std->pMVAST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pRedBCST',
            $this->conditionalNumberFormatting($std->pRedBCST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCST',
            $this->conditionalNumberFormatting($std->vBCST),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pICMSST',
            $this->conditionalNumberFormatting($std->pICMSST, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSST',
            $this->conditionalNumberFormatting($std->vICMSST),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCPST',
            $this->conditionalNumberFormatting($std->vBCFCPST),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'pFCPST',
            $this->conditionalNumberFormatting($std->pFCPST, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP) ST"
        );
        $this->dom->addChild(
            $icms,
            'vFCPST',
            $this->conditionalNumberFormatting($std->vFCPST),
            false,
            "{$identificador} [item $std->item] Valor do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDeson',
            $this->conditionalNumberFormatting($std->vICMSDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMS',
            $std->motDesICMS,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS40
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS40
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS40(\stdClass $std, $identificador)
    {
        $icms = $this->dom->createElement('ICMS40');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS $std->CST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDeson',
            $this->conditionalNumberFormatting($std->vICMSDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMS',
            $std->motDesICMS,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS51
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS51
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS51(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;

        $icms = $this->dom->createElement('ICMS51');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 51"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            false,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pRedBC',
            $this->conditionalNumberFormatting($std->pRedBC, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            false,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            false,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMSOp',
            $this->conditionalNumberFormatting($std->vICMSOp),
            false,
            "{$identificador} [item $std->item] Valor do ICMS da Operação"
        );
        $this->dom->addChild(
            $icms,
            'pDif',
            $this->conditionalNumberFormatting($std->pDif, 4),
            false,
            "{$identificador} [item $std->item] Percentual do diferimento"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDif',
            $this->conditionalNumberFormatting($std->vICMSDif),
            false,
            "{$identificador} [item $std->item] Valor do ICMS diferido"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            false,
            "{$identificador} [item $std->item] Valor do ICMS realmente devido"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCP',
            $this->conditionalNumberFormatting($std->vBCFCP),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $this->conditionalNumberFormatting($std->vFCP),
            false,
            "{$identificador} [item $std->item] Valor do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCPDif',
            $this->conditionalNumberFormatting($std->pFCPDif),
            false,
            "{$identificador} [item $std->item] Percentual do diferimento "
                . "do ICMS relativo ao Fundo de Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCPDif',
            $this->conditionalNumberFormatting($std->vFCPDif),
            false,
            "{$identificador} [item $std->item] Valor do ICMS relativo ao "
                . "Fundo de Combate à Pobreza (FCP) diferido"
        );
        $this->dom->addChild(
            $icms,
            'vFCPEfet',
            $this->conditionalNumberFormatting($std->vFCPEfet),
            false,
            "{$identificador} [item $std->item] Valor efetivo do ICMS relativo "
                . "ao Fundo de Combate à Pobreza (FCP)"
        );

        return $icms;
    }


    /**
     * Função que cria a tag ICMS60
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS60
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS60(\stdClass $std, $identificador)
    {
        $icms = $this->dom->createElement('ICMS60');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 60"
        );
        $this->dom->addChild(
            $icms,
            'vBCSTRet',
            $this->conditionalNumberFormatting($std->vBCSTRet),
            false,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST retido"
        );
        $this->dom->addChild(
            $icms,
            'pST',
            $this->conditionalNumberFormatting($std->pST, 4),
            false,
            "{$identificador} [item $std->item] Valor do ICMS ST retido"
        );
        $this->dom->addChild(
            $icms,
            'vICMSSubstituto',
            $this->conditionalNumberFormatting($std->vICMSSubstituto),
            false,
            "{$identificador} [item $std->item] Valor do ICMS próprio do Substituto"
        );
        $this->dom->addChild(
            $icms,
            'vICMSSTRet',
            $this->conditionalNumberFormatting($std->vICMSSTRet),
            false,
            "{$identificador} [item $std->item] Valor do ICMS ST retido"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCPSTRet',
            $this->conditionalNumberFormatting($std->vBCFCPSTRet),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo "
                . "do FCP retido anteriormente por ST"
        );
        $this->dom->addChild(
            $icms,
            'pFCPSTRet',
            $this->conditionalNumberFormatting($std->pFCPSTRet, 4),
            false,
            "{$identificador} [item $std->item] Percentual do FCP retido "
                . "anteriormente por Substituição Tributária"
        );
        $this->dom->addChild(
            $icms,
            'vFCPSTRet',
            $this->conditionalNumberFormatting($std->vFCPSTRet),
            false,
            "{$identificador} [item $std->item] Valor do FCP retido por "
                . "Substituição Tributária"
        );
        $this->dom->addChild(
            $icms,
            'pRedBCEfet',
            $this->conditionalNumberFormatting($std->pRedBCEfet, 4),
            false,
            "{$identificador} [item $std->item] Percentual de redução "
                . "para obtenção da base de cálculo efetiva (vBCEfet)"
        );
        $this->dom->addChild(
            $icms,
            'vBCEfet',
            $this->conditionalNumberFormatting($std->vBCEfet),
            false,
            "{$identificador} [item $std->item] base de calculo efetiva"
        );
        $this->dom->addChild(
            $icms,
            'pICMSEfet',
            $this->conditionalNumberFormatting($std->pICMSEfet, 4),
            false,
            "{$identificador} [item $std->item] Alíquota do ICMS na operação a consumidor final"
        );
        $this->dom->addChild(
            $icms,
            'vICMSEfet',
            $this->conditionalNumberFormatting($std->vICMSEfet),
            false,
            "{$identificador} [item $std->item] Valor do ICMS efetivo"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS70
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS70
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS70(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;
        $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
        $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

        $icms = $this->dom->createElement('ICMS70');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 70"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pRedBC',
            $this->conditionalNumberFormatting($std->pRedBC, 4),
            true,
            "{$identificador} [item $std->item] Percentual da Redução de BC"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            true,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCP',
            $this->conditionalNumberFormatting($std->vBCFCP),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $std->vFCP,
            false,
            "{$identificador} [item $std->item] Valor do FCP"
        );
        $this->dom->addChild(
            $icms,
            'modBCST',
            $std->modBCST,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pMVAST',
            $this->conditionalNumberFormatting($std->pMVAST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pRedBCST',
            $this->conditionalNumberFormatting($std->pRedBCST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCST',
            $this->conditionalNumberFormatting($std->vBCST),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pICMSST',
            $this->conditionalNumberFormatting($std->pICMSST, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSST',
            $this->conditionalNumberFormatting($std->vICMSST),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCPST',
            $this->conditionalNumberFormatting($std->vBCFCPST),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'pFCPST',
            $this->conditionalNumberFormatting($std->pFCPST, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP) ST"
        );
        $this->dom->addChild(
            $icms,
            'vFCPST',
            $this->conditionalNumberFormatting($std->vFCPST),
            false,
            "{$identificador} [item $std->item] Valor do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDeson',
            $this->conditionalNumberFormatting($std->vICMSDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMS',
            $std->motDesICMS,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vICMSSTDeson',
            $this->conditionalNumberFormatting($std->vICMSSTDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS- ST desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMSST',
            $std->motDesICMSST ?? null,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS- ST"
        );

        return $icms;
    }

    /**
     * Função que cria a tag ICMS90
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMS90
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMS90(\stdClass $std, $identificador)
    {
        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;
        $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
        $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

        $icms = $this->dom->createElement('ICMS90');

        $this->dom->addChild(
            $icms,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icms,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS = 90"
        );
        $this->dom->addChild(
            $icms,
            'modBC',
            $std->modBC,
            false,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            false,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'pRedBC',
            $this->conditionalNumberFormatting($std->pRedBC, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC"
        );
        $this->dom->addChild(
            $icms,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            false,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icms,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            false,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCP',
            $this->conditionalNumberFormatting($std->vBCFCP),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icms,
            'pFCP',
            $this->conditionalNumberFormatting($std->pFCP, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP)"
        );
        $this->dom->addChild(
            $icms,
            'vFCP',
            $this->conditionalNumberFormatting($std->vFCP),
            false,
            "{$identificador} [item $std->item] Valor do FCP"
        );
        $this->dom->addChild(
            $icms,
            'modBCST',
            $std->modBCST,
            false,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pMVAST',
            $this->conditionalNumberFormatting($std->pMVAST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pRedBCST',
            $this->conditionalNumberFormatting($std->pRedBCST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCST',
            $this->conditionalNumberFormatting($std->vBCST),
            false,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'pICMSST',
            $this->conditionalNumberFormatting($std->pICMSST, 4),
            false,
            "{$identificador} [item $std->item] Alíquota do imposto do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSST',
            $this->conditionalNumberFormatting($std->vICMSST),
            false,
            "{$identificador} [item $std->item] Valor do ICMS ST"
        );
        $this->dom->addChild(
            $icms,
            'vBCFCPST',
            $this->conditionalNumberFormatting($std->vBCFCPST),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'pFCPST',
            $this->conditionalNumberFormatting($std->pFCPST, 4),
            false,
            "{$identificador} [item $std->item] Percentual do Fundo de "
                . "Combate à Pobreza (FCP) ST"
        );
        $this->dom->addChild(
            $icms,
            'vFCPST',
            $this->conditionalNumberFormatting($std->vFCPST),
            false,
            "{$identificador} [item $std->item] Valor do FCP ST"
        );
        $this->dom->addChild(
            $icms,
            'vICMSDeson',
            $this->conditionalNumberFormatting($std->vICMSDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMS',
            $std->motDesICMS,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS"
        );
        $this->dom->addChild(
            $icms,
            'vICMSSTDeson',
            $this->conditionalNumberFormatting($std->vICMSSTDeson),
            false,
            "{$identificador} [item $std->item] Valor do ICMS- ST desonerado"
        );
        $this->dom->addChild(
            $icms,
            'motDesICMSST',
            $std->motDesICMSST ?? null,
            false,
            "{$identificador} [item $std->item] Motivo da desoneração do ICMS- ST"
        );

        return $icms;
    }
}

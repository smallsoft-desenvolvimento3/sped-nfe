<?php

/**
 * Trait Helper para impostos relacionados a ICMS ST
 * Esta trait basica está estruturada para montar as tags de ICMS ST para o
 * layout versão 4.00, os demais modelos serão derivados deste
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

trait ICMSST
{
    /**
     * Grupo de Repasse de ICMSST retido anteriormente em operações
     * interestaduais com repasses através do Substituto Tributário
     * NOTA: ajustado NT 2018.005
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMSST N10b pai N01
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagICMSST(\stdClass $std)
    {
        $possible = [
            'item',
            'orig',
            'CST',
            'vBCSTRet',
            'vICMSSTRet',
            'vBCSTDest',
            'vICMSSTDest',
            'vBCFCPSTRet',
            'pFCPSTRet',
            'vFCPSTRet',
            'pST',
            'vICMSSubstituto',
            'pRedBCEfet',
            'vBCEfet',
            'pICMSEfet',
            'vICMSEfet'
        ];

        $std = $this->equilizeParameters($std, $possible);

        $this->stdTot->vFCPSTRet += (float) !empty($std->vFCPSTRet) ? $std->vFCPSTRet : 0;

        $icmsST = $this->dom->createElement("ICMSST");

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'N10b <ICMSST> -';

        $this->dom->addChild(
            $icmsST,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icmsST,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS 41 ou 60"
        );
        $this->dom->addChild(
            $icmsST,
            'vBCSTRet',
            $this->conditionalNumberFormatting($std->vBCSTRet),
            true,
            "{$identificador} [item $std->item] Valor do BC do ICMS ST retido na UF remetente"
        );
        $this->dom->addChild(
            $icmsST,
            'pST',
            $this->conditionalNumberFormatting($std->pST, 4),
            false,
            "{$identificador} [item $std->item] Alíquota suportada pelo Consumidor Final"
        );
        $this->dom->addChild(
            $icmsST,
            'vICMSSubstituto',
            $this->conditionalNumberFormatting($std->vICMSSubstituto),
            false,
            "{$identificador} [item $std->item] Valor do ICMS próprio do Substituto"
        );
        $this->dom->addChild(
            $icmsST,
            'vICMSSTRet',
            $this->conditionalNumberFormatting($std->vICMSSTRet),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST retido na UF remetente"
        );
        $this->dom->addChild(
            $icmsST,
            'vBCFCPSTRet',
            $this->conditionalNumberFormatting($std->vBCFCPSTRet),
            false,
            "{$identificador} [item $std->item] Valor da Base de Cálculo do FCP"
        );
        $this->dom->addChild(
            $icmsST,
            'pFCPSTRet',
            $this->conditionalNumberFormatting($std->pFCPSTRet, 4),
            false,
            "{$identificador} [item $std->item] Percentual do FCP retido"
        );
        $this->dom->addChild(
            $icmsST,
            'vFCPSTRet',
            $this->conditionalNumberFormatting($std->vFCPSTRet),
            false,
            "{$identificador} [item $std->item] Valor do FCP retido"
        );
        $this->dom->addChild(
            $icmsST,
            'vBCSTDest',
            $this->conditionalNumberFormatting($std->vBCSTDest),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST da UF destino"
        );
        $this->dom->addChild(
            $icmsST,
            'vICMSSTDest',
            $this->conditionalNumberFormatting($std->vICMSSTDest),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST da UF destino"
        );
        $this->dom->addChild(
            $icmsST,
            'pRedBCEfet',
            $this->conditionalNumberFormatting($std->pRedBCEfet, 4),
            false,
            "{$identificador} [item $std->item] Percentual de redução da base de cálculo efetiva"
        );
        $this->dom->addChild(
            $icmsST,
            'vBCEfet',
            $this->conditionalNumberFormatting($std->vBCEfet),
            false,
            "{$identificador} [item $std->item] Valor da base de cálculo efetiva"
        );
        $this->dom->addChild(
            $icmsST,
            'pICMSEfet',
            $this->conditionalNumberFormatting($std->pICMSEfet, 4),
            false,
            "{$identificador} [item $std->item] Alíquota do ICMS efetiva"
        );
        $this->dom->addChild(
            $icmsST,
            'vICMSEfet',
            $this->conditionalNumberFormatting($std->vICMSEfet),
            false,
            "{$identificador} [item $std->item] Valor do ICMS efetivo"
        );

        //caso exista a tag aICMS[$std->item] inserir nela caso contrario criar
        if (!empty($this->aICMS[$std->item])) {
            $tagIcms = $this->aICMS[$std->item];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }

        $this->dom->appChild($tagIcms, $icmsST, "Inserindo ICMSST em ICMS[$std->item]");
        $this->aICMS[$std->item] = $tagIcms;

        return $tagIcms;
    }
}

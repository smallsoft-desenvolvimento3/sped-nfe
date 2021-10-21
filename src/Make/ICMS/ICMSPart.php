<?php

/**
 * Trait Helper para impostos relacionados a ICMSPart
 * Esta trait basica está estruturada para montar as tags de ICMSPart para o
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

trait ICMSPart
{

    /**
     * Grupo de Partilha do ICMS entre a UF de origem e UF de destino ou
     * a UF definida na legislação. N10a pai N01
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMSPart
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagICMSPart(\stdClass $std)
    {
        $possible = [
            'item',
            'orig',
            'CST',
            'modBC',
            'vBC',
            'pRedBC',
            'pICMS',
            'vICMS',
            'modBCST',
            'pMVAST',
            'pRedBCST',
            'vBCST',
            'pICMSST',
            'vICMSST',
            'pBCOp',
            'UFST'
        ];

        $std = $this->equilizeParameters($std, $possible);

        $this->stdTot->vBC += (float) !empty($std->vBC) ? $std->vBC : 0;
        $this->stdTot->vICMS += (float) !empty($std->vICMS) ? $std->vICMS : 0;
        $this->stdTot->vBCST += (float) !empty($std->vBCST) ? $std->vBCST : 0;
        $this->stdTot->vST += (float) !empty($std->vICMSST) ? $std->vICMSST : 0;

        $icmsPart = $this->buildICMSPart($std);

        // caso exista a tag aICMS[$std->item] inserir nela caso contrario criar
        if (!empty($this->aICMS[$std->item])) {
            $tagIcms = $this->aICMS[$std->item];
        } else {
            $tagIcms = $this->dom->createElement('ICMS');
        }

        $this->dom->appChild($tagIcms, $icmsPart, "Inserindo ICMSPart em ICMS[$std->item]");
        $this->aICMS[$std->item] = $tagIcms;

        return $tagIcms;
    }

    /**
     * Função que cria a tag ICMS00
     * tag NFe/infNFe/det[]/imposto/ICMS/ICMSPart
     * @param \stdClass $std
     * @return \DOMElement
     */
    private function buildICMSPart(\stdClass $std)
    {
        $icmsPart = $this->dom->createElement('ICMSPart');

        /**
         * Identificador da Tag para mostrar nos erros
         */
        $identificador = 'N10a <ICMSPart> -';

        $this->dom->addChild(
            $icmsPart,
            'orig',
            $std->orig,
            true,
            "{$identificador} [item $std->item] Origem da mercadoria"
        );
        $this->dom->addChild(
            $icmsPart,
            'CST',
            $std->CST,
            true,
            "{$identificador} [item $std->item] Tributação do ICMS 10 ou 90"
        );
        $this->dom->addChild(
            $icmsPart,
            'modBC',
            $std->modBC,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS"
        );
        $this->dom->addChild(
            $icmsPart,
            'vBC',
            $this->conditionalNumberFormatting($std->vBC),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS"
        );
        $this->dom->addChild(
            $icmsPart,
            'pRedBC',
            $this->conditionalNumberFormatting($std->pRedBC, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC"
        );
        $this->dom->addChild(
            $icmsPart,
            'pICMS',
            $this->conditionalNumberFormatting($std->pICMS, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto"
        );
        $this->dom->addChild(
            $icmsPart,
            'vICMS',
            $this->conditionalNumberFormatting($std->vICMS),
            true,
            "{$identificador} [item $std->item] Valor do ICMS"
        );
        $this->dom->addChild(
            $icmsPart,
            'modBCST',
            $std->modBCST,
            true,
            "{$identificador} [item $std->item] Modalidade de determinação da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'pMVAST',
            $this->conditionalNumberFormatting($std->pMVAST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da margem de valor Adicionado do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'pRedBCST',
            $this->conditionalNumberFormatting($std->pRedBCST, 4),
            false,
            "{$identificador} [item $std->item] Percentual da Redução de BC do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'vBCST',
            $this->conditionalNumberFormatting($std->vBCST),
            true,
            "{$identificador} [item $std->item] Valor da BC do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'pICMSST',
            $this->conditionalNumberFormatting($std->pICMSST, 4),
            true,
            "{$identificador} [item $std->item] Alíquota do imposto do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'vICMSST',
            $this->conditionalNumberFormatting($std->vICMSST),
            true,
            "{$identificador} [item $std->item] Valor do ICMS ST"
        );
        $this->dom->addChild(
            $icmsPart,
            'pBCOp',
            $this->conditionalNumberFormatting($std->pBCOp, 4),
            true,
            "{$identificador} [item $std->item] Percentual da BC operação própria"
        );
        $this->dom->addChild(
            $icmsPart,
            'UFST',
            $std->UFST,
            true,
            "{$identificador} [item $std->item] UF para qual é devido o ICMS ST"
        );

        return $icmsPart;
    }
}

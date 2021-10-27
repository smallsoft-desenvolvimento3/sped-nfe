<?php

/**
 * Trait Helper para tags relacionados a adições
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

trait Adi
{
    /**
     * @var array<\DOMElement>
     */
    protected $aAdi = [];

    /**
     * Adições I25 pai I18
     * tag NFe/infNFe/det[]/prod/DI/adi
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagadi(\stdClass $std)
    {
        $possible = [
            'item',
            'nDI',
            'nAdicao',
            'nSeqAdic',
            'cFabricante',
            'vDescDI',
            'nDraw'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I25 <adi> - [item $std->item]:";

        $adi = $this->dom->createElement('adi');

        $this->dom->addChild(
            $adi,
            "nAdicao",
            $std->nAdicao,
            true,
            "{$identificador} Número da Adição"
        );
        $this->dom->addChild(
            $adi,
            "nSeqAdic",
            $std->nSeqAdic,
            true,
            "{$identificador} Número sequencial do item dentro da Adição"
        );
        $this->dom->addChild(
            $adi,
            "cFabricante",
            $std->cFabricante,
            true,
            "{$identificador} Código do fabricante estrangeiro"
        );
        $this->dom->addChild(
            $adi,
            "vDescDI",
            $this->conditionalNumberFormatting($std->vDescDI),
            false,
            "{$identificador} Valor do desconto do item da DI Adição"
        );
        $this->dom->addChild(
            $adi,
            "nDraw",
            $std->nDraw,
            false,
            "{$identificador} Número do ato concessório de Drawback"
        );
        $this->aAdi[$std->item][$std->nDI][] = $adi;
        //colocar a adi em seu DI respectivo
        $nodeDI = $this->aDI[$std->item][$std->nDI];
        $this->dom->appChild($nodeDI, $adi);
        $this->aDI[$std->item][$std->nDI] = $nodeDI;
        return $adi;
    }
}

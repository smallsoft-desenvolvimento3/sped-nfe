<?php

/**
 * Trait Helper para tags relacionados ao detalhamento de armas
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

trait Arma
{
    /**
     * @var array<\DOMElement>
     */
    protected $aArma = [];

    /**
     * Detalhamento de armas L01 pai I90
     * tag NFe/infNFe/det[]/prod/arma (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagarma(\stdClass $std)
    {
        $possible = [
            'item',
            'nAR',
            'tpArma',
            'nSerie',
            'nCano',
            'descr'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "L01 <arma> - [item $std->item]:";

        $arma = $this->dom->createElement('arma');

        $this->dom->addChild(
            $arma,
            "tpArma",
            $std->tpArma,
            true,
            "{$identificador} Indicador do tipo de arma de fogo"
        );
        $this->dom->addChild(
            $arma,
            "nSerie",
            $std->nSerie,
            true,
            "{$identificador} Número de série da arma"
        );
        $this->dom->addChild(
            $arma,
            "nCano",
            $std->nCano,
            true,
            "{$identificador} Número de série do cano"
        );
        $this->dom->addChild(
            $arma,
            "descr",
            $std->descr,
            true,
            "{$identificador} Descrição completa da arma, compreendendo: calibre, marca, capacidade, "
                . "tipo de funcionamento, comprimento e demais elementos que "
                . "permitam a sua perfeita identificação."
        );
        $this->aArma[$std->item][$std->nAR] = $arma;
        return $arma;
    }
}

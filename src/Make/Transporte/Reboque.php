<?php

/**
 * Trait Helper para tags relacionados ao reboque de transporte
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

trait Reboque
{
    /**
     * Grupo Reboque X22 pai X17.1
     * tag NFe/infNFe/transp/reboque (opcional)
     * Ajustado NT 2020.005 v1.20
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagreboque(\stdClass $std)
    {
        $possible = [
            'placa',
            'UF',
            'RNTC'
        ];
        $std = $this->equilizeParameters($std, $possible);
        $reboque = $this->dom->createElement("reboque");
        $this->dom->addChild(
            $reboque,
            "placa", // X23
            $std->placa,
            true,
            "Placa do Veículo Reboque"
        );
        $this->dom->addChild(
            $reboque,
            "UF", // X24
            $std->UF,
            false,
            "Sigla da UF do Veículo Reboque"
        );
        $this->dom->addChild(
            $reboque,
            "RNTC", // X25
            $std->RNTC,
            false,
            "Registro Nacional de Transportador de Carga (ANTT) do Veículo Reboque"
        );
        $this->dom->appChild(
            $this->transp,
            $reboque,
            'A tag transp deveria ter sido carregada primeiro.'
        );
        return $reboque;
    }
}

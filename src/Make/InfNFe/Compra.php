<?php

/**
 * Trait Helper para tags relacionados a Compra
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

namespace NFePHP\NFe\Make\InfNFe;

trait Compra
{
    /**
     * @var \DOMElement
     */
    protected $compra;

    /**
     * Grupo Compra ZB01 pai A01
     * tag NFe/infNFe/compra (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagcompra(\stdClass $std)
    {
        $possible = ['xNEmp', 'xPed', 'xCont'];
        $std = $this->equilizeParameters($std, $possible);
        $this->compra = $this->dom->createElement("compra");
        $this->dom->addChild(
            $this->compra,
            "xNEmp",
            $std->xNEmp,
            false,
            "Nota de Empenho"
        );
        $this->dom->addChild(
            $this->compra,
            "xPed",
            $std->xPed,
            false,
            "Pedido"
        );
        $this->dom->addChild(
            $this->compra,
            "xCont",
            $std->xCont,
            false,
            "Contrato"
        );
        return $this->compra;
    }
}

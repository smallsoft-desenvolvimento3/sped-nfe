<?php

/**
 * Trait Helper para tags relacionados ao transporte
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

trait Transp
{
    /**
     * @var \DOMElement
     */
    protected $transp;

    /**
     * Grupo Informações do Transporte X01 pai A01
     * tag NFe/infNFe/transp (obrigatório)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagtransp(\stdClass $std)
    {
        $this->transp = $this->dom->createElement("transp");
        $this->dom->addChild(
            $this->transp,
            "modFrete",
            $std->modFrete,
            true,
            "Modalidade do frete"
        );
        return $this->transp;
    }
}

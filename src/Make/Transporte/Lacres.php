<?php

/**
 * Trait Helper para tags relacionados aos lacres
 *
 * Essa trait depende da \NFePHP\NFe\Make\Transporte\Vol
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

trait Lacres
{
    /**
     * Grupo Lacres X33 pai X26
     * tag NFe/infNFe/transp/vol/lacres (opcional)
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function taglacres(\stdClass $std)
    {
        $lacre = $this->dom->createElement("lacres");

        $this->dom->addChild(
            $lacre,
            "nLacre",
            $std->nLacre,
            true,
            "Número dos Lacres"
        );
        $this->dom->appChild($this->aVol[$std->item], $lacre, "Inclusão do node lacres");
        return $lacre;
    }
}

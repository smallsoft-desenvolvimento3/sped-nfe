<?php

/**
 * Trait Helper para tags relacionados a observações do fisco
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

namespace NFePHP\NFe\Make\InfAdic;

trait ObsFisco
{

    /**
     * Grupo Campo de uso livre do Fisco Z07 pai Z01
     * tag NFe/infNFe/infAdic/obsFisco (opcional)
     * O método taginfAdic deve ter sido carregado antes
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagobsFisco(\stdClass $std)
    {
        $possible = ['xCampo', 'xTexto'];
        $std = $this->equilizeParameters($std, $possible);
        $this->buildInfAdic();
        $obsFisco = $this->dom->createElement("obsFisco");
        $obsFisco->setAttribute("xCampo", $std->xCampo);
        $this->dom->addChild(
            $obsFisco,
            "xTexto",
            $std->xTexto,
            true,
            "Conteúdo do campo"
        );
        $this->dom->appChild($this->infAdic, $obsFisco, '');
        return $obsFisco;
    }
}

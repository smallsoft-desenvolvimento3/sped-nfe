<?php

/**
 * Trait Helper para tags relacionados ao processo referenciado
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

trait ProcRef
{

    /**
     * Grupo Processo referenciado Z10 pai Z01 (NT2012.003)
     * tag NFe/infNFe/procRef (opcional)
     * O método taginfAdic deve ter sido carregado antes
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagprocRef(\stdClass $std)
    {
        $possible = ['nProc', 'indProc'];
        $std = $this->equilizeParameters($std, $possible);
        $this->buildInfAdic();
        $procRef = $this->dom->createElement("procRef");
        $this->dom->addChild(
            $procRef,
            "nProc",
            $std->nProc,
            true,
            "Identificador do processo ou ato concessório"
        );
        $this->dom->addChild(
            $procRef,
            "indProc",
            $std->indProc,
            true,
            "Indicador da origem do processo"
        );
        $this->dom->appChild($this->infAdic, $procRef, '');
        return $procRef;
    }
}

<?php

/**
 * Trait Helper para tags relacionados a informações de exportação
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

use NFePHP\Common\Strings;

trait DetExport
{
    /**
     * @var array<\DOMElement>
     */
    protected $aDetExport = [];

    /**
     * Grupo de informações de exportação para o item I50 pai I01
     * tag NFe/infNFe/det[]/prod/detExport
     * @param \stdClass $std
     * @return \DOMElement
     */
    public function tagdetExport(\stdClass $std)
    {
        $possible = [
            'item',
            'nDraw'
        ];
        $std = $this->equilizeParameters($std, $possible);

        $identificador = "I50 <detExport> - [item $std->item]:";

        $detExport = $this->dom->createElement("detExport");

        $this->dom->addChild(
            $detExport,
            "nDraw",
            Strings::onlyNumbers($std->nDraw),
            false,
            "{$identificador} Número do ato concessório de Drawback"
        );
        $this->aDetExport[$std->item][] = $detExport;
        return $detExport;
    }
}

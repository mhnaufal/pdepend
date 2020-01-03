<?php
/**
 * This file is part of PDepend.
 *
 * Copyright (c) 2008-2017 Manuel Pichler <mapi@pdepend.org>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Manuel Pichler nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright 2008-2017 Manuel Pichler. All rights reserved.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

namespace PDepend\Source\Language\PHP;

use PDepend\AbstractTest;
use PDepend\Source\AST\ASTArrayElement;
use PDepend\Source\AST\ASTHeredoc;
use PDepend\Source\AST\ASTLiteral;
use PDepend\Source\AST\ASTVariable;

/**
 * Test case for the {@link \PDepend\Source\Language\PHP\PHPParserVersion73} class.
 *
 * @copyright 2008-2017 Manuel Pichler. All rights reserved.
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 * @covers \PDepend\Source\Language\PHP\PHPParserVersion73
 * @group unittest
 */
class PHPParserVersion73Test extends AbstractTest
{
    /**
     * @return void
     */
    public function testHereDocAndNowDoc()
    {
        /** @var ASTHeredoc $heredoc */
        $heredoc = $this->getFirstNodeOfTypeInFunction('','PDepend\\Source\\AST\\ASTArray');
        $arrayElements = $heredoc->getChildren();
        $children = $arrayElements[0]->getChildren();
        $children = $children[0]->getChildren();
        /** @var ASTLiteral $literal */
        $literal = $children[0];

        $this->assertSame('foobar!', $literal->getImage());

        $children = $arrayElements[1]->getChildren();
        $children = $children[0]->getChildren();
        /** @var ASTLiteral $literal */
        $literal = $children[0];

        $this->assertSame('second,', $literal->getImage());
    }

    /**
     * @return void
     */
    public function testDestructuringArrayReference()
    {
        $functionChildren = $this->getFirstFunctionForTestCase()->getChildren();
        $statements = $functionChildren[1]->getChildren();
        $assignments = $statements[1]->getChildren();
        $listElements = $assignments[0]->getChildren();
        $children = $listElements[0]->getChildren();
        /** @var ASTArrayElement $aElement */
        $aElement = $children[0];
        $arrayElement = $children[1];
        $children = $arrayElement->getChildren();
        $subElements = $children[0]->getChildren();
        /** @var ASTArrayElement $bElement */
        $bElement = $subElements[0];
        /** @var ASTArrayElement $cElement */
        $cElement = $subElements[1];

        $aElements = $aElement->getChildren();
        /** @var ASTVariable $aVariable */
        $aVariable = $aElements[0];

        $bElements = $bElement->getChildren();
        /** @var ASTVariable $bVariable */
        $bVariable = $bElements[0];

        $cElements = $cElement->getChildren();
        /** @var ASTVariable $cVariable */
        $cVariable = $cElements[0];

        $this->assertTrue($aElement->isByReference());
        $this->assertSame('$a', $aVariable->getImage());

        $this->assertFalse($bElement->isByReference());
        $this->assertSame('$b', $bVariable->getImage());

        $this->assertTrue($cElement->isByReference());
        $this->assertSame('$c', $cVariable->getImage());
    }
}

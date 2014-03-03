<?php namespace BehatAppTests\Tests;


class BehatFormatterTest extends BehatBaseTests {

    public function shouldBe()
    {
        $out = <<<HEREDOC
Feature: ls<br>&nbsp;&nbsp;  Scenario: List files in directory<br>&nbsp;&nbsp;&nbsp;&nbsp;    Given I am in a directory "tmp"<br>&nbsp;&nbsp;&nbsp;&nbsp;    And I have a file named "foo"<br>&nbsp;&nbsp;&nbsp;&nbsp;    And I have a file named "bar"<br>&nbsp;&nbsp;&nbsp;&nbsp;    When I run "ls"<br>&nbsp;&nbsp;&nbsp;&nbsp;    Then I should get:<br>&nbsp;&nbsp;&nbsp;&nbsp;    """<br>&nbsp;&nbsp;&nbsp;&nbsp;    bar<br>&nbsp;&nbsp;&nbsp;&nbsp;    foo<br>&nbsp;&nbsp;&nbsp;&nbsp;    """<br>
HEREDOC;
        return $out;
    }

    public function testPlainToHtml()
    {
        $in             = $this->makePlainTextTest();
        $shouldBe       = $this->shouldBe();
        $output         = $this->formatter->plainToHtml($in);
        $this->assertEquals($output, $shouldBe);
    }

}
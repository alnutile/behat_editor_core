<?php namespace BehatAppTests;


class BehatFormatterTest extends \BehatAppTests\BaseTests {

    public function shouldBe()
    {
        $out = <<<HEREDOC
@example <br><br>Feature: Test P 2 HTML<br>&nbsp;&nbsp;  Scenario: Test 1<br>&nbsp;&nbsp;&nbsp;&nbsp;    Given I am on "/test"<br>&nbsp;&nbsp;&nbsp;&nbsp;    Then I should see "test"<br>
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
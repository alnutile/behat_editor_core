<?php namespace BehatAppTests\Tests;


class BehatFormatterTest extends BehatBaseTests {

    public function testPlainToHtml()
    {
        $in             = $this->makePlainTextTest();
        $shouldBe       = $this->makeHtmlTestOutput();
        $output         = $this->formatter->plainToHtml($in);
        $this->assertEquals($shouldBe, $output);
    }

    public function testHtmlToPlain()
    {
        $in             = $this->makeHtmlTestOutput();
        $shouldBe       = $this->makePlainTextTest();
        $output         = $this->formatter->htmlToPlain($in);
        $this->assertEquals($shouldBe, $output);
    }

}
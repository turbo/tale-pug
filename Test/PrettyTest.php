<?php

namespace Tale\Test\Pug;

use Tale\Pug\Compiler;
use Tale\Pug\Renderer;
use Tale\Pug\Parser;

class PrettyTest extends \PHPUnit_Framework_TestCase
{

    /** @var \Tale\Pug\Renderer */
    private $renderer;

    public function setUp()
    {

        $this->renderer = new Renderer([
            'adapter_options' => [
                'path' => __DIR__.'/cache/pretty',
            ],
            'pretty' => true,
            'paths' => [__DIR__.'/views/pretty']
        ]);
    }

    public function testBasic()
    {

        $phtml = <<<'PHTML'
<!DOCTYPE html>
<html<?php $__value = isset($lang) ? $lang : false; if (!\Tale\Pug\Compiler\is_null_or_false($__value)) echo ' lang='.\Tale\Pug\Compiler\build_value($__value, '"', true); unset($__value);?>>
  <head>
    <title>
      <?=htmlentities(isset($title) ? $title : '', \ENT_QUOTES, 'UTF-8')?>
    </title>
    <link rel="stylesheet" href="/some-style.css">
  </head>
  <body>
    <h1>
      Some Header
    </h1>
    <p>
      Some multiline
      text that will just span
      over as many lines as it fucking likes!
    </p>
    <h2>
      A node with a single zero
    </h2>
    <p>
      0
    </p>
    <script src="/some-script.css"></script>
  </body>
</html>
PHTML;


        $this->assertEquals(str_replace("\r", '', $phtml), $this->renderer->compileFile(
            'basic'
        ));
    }

    public function testSingle()
    {

        $phtml = <<<'PHTML'
<div class="container">
  <div class="row">
    <div class="col-md-6 col-sm-3">
      <p>
        Some content
      </p>
    </div>
    <div class="col-md-6 col-sm-3">
      <p>
        Some content
      </p>
    </div>
    <div class="col-md-6 col-sm-3">
      <p>
        Some content
      </p>
    </div>
  </div>
</div>
PHTML;

        $this->assertEquals(str_replace("\r", '', $phtml), $this->renderer->compileFile(
            'single'
        ));
    }

    public function testForcedInlineTags()
    {

        $phtml = <<<'PHTML'
<?php $content = "This is some Content.\n\n    This comment contains own whitespace to preserve."?>
<some-container>
  <pre><?=htmlentities(isset($content) ? $content : '', \ENT_QUOTES, 'UTF-8')?></pre>
  <pre>Some <strong>interpolated content</strong></pre>
  <div>
    <?=htmlentities(isset($content) ? $content : '', \ENT_QUOTES, 'UTF-8')?>
  </div>
  <div>
    Some 
    <strong>
      interpolated content
    </strong>
  </div>
  <code><?=htmlentities(isset($content) ? $content : '', \ENT_QUOTES, 'UTF-8')?></code>
  <code>Some <strong>interpolated content</strong></code>
</some-container>
PHTML;

        $this->assertEquals(str_replace("\r", '', $phtml), $this->renderer->compileFile(
            'forced-inline'
        ));
    }

}
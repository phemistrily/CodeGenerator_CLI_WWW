<?php
class Code
{
    private $code;
    private $options = [
    'allowCharset' => 'abcdefghijklmnopqrstuwvxyzABCDEFGIJKLMNOPQRSTUWVXYZ0123456789',
    'regexCharacters' => '\*'
    ];

    public function __construct(array $options = null)
    {
        $this->setOptions($options);
    
    }

    private function setOptions($options)
    {
        if(!empty($options) && is_array($options)) {
            $this->options = array_merge($this->options, $options);
        }
        $this->charsetLength = strlen($this->options['allowCharset']);
    }

    public function createCode(int $lengthOfCode)
    {
        $this->recognizeCodeCharacters($lengthOfCode);
        for($key = 0; $key < strlen($this->code); $key++)
        {
            if($this->code[$key] == ' ') {
                $this->code[$key] = $this->options['allowCharset'][rand(0, $this->charsetLength-1)];
            }
        }
        return $this->code;
    }

    private function recognizeCodeCharacters($lengthLeft)
    {
        $this->code = '';
        $regexCharacters = str_split($this->options['regexCharacters']);
        while($lengthLeft > 0)
        {
            foreach ($regexCharacters as $key => $character) {
                switch ($character) {
                case '\\':
                    if($regexCharacters[$key+1] == '*') {
                        $this->addNullCharactersToCode($lengthLeft);
                        $lengthLeft -= $lengthLeft;
                    }
                    break;
                case '0':
                case '1':
                case '2':
                case '3':
                case '4':
                case '5':
                case '6':
                case '7':
                case '8':
                case '9':
                case '*':

                    break;
                default:
                      $this->code .= $character;
                      $lengthLeft--;
                    break;
                }
            }
        }
    }

    private function addNullCharactersToCode($lengthOfNulls)
    {
        for($i = 0; $i < $lengthOfNulls; $i++) {
            $this->code .= ' ';
        }
    }
}

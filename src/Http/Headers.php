<?php 
namespace Idm\PiperLink\Http;

class Headers implements \Iterator {

    // array of all http headers indexed by name.
    private array $fields = [];
    // dictionary of all headers, with lower case version as key and original key as value.
    private array $fieldsLC = [];    
    // if set to true, the php headers will be set too.
    public bool $flushOnSet = false;

    public function parse() {
        $flushOnSet =  $this->flushOnSet;
        $this->flushOnSet = false; 
        foreach (getallheaders() as $key => $value) {
            $this->set($key, $value);
        }
        $this->flushOnSet = $flushOnSet;
    }

    public function set(string $name, ?string $value): Headers {
        $lower = strtolower($name);
        // still exists => delete old value
        if (isset($this->fieldsLC[$lower])) {
            unset($this->fields[$this->fieldsLC[$lower]]);
            if ($value !== null) {
                unset($this->fieldsLC[$lower]);
            }
        }
        if ($value !== null) {
            // add name to "lower" dictionary
            $this->fieldsLC[$lower] = $name;
            // add value to fields
            $this->fields[$name] = $value;
            if ($this->flushOnSet && !headers_sent()) {
                header($name.": ".$value);
            }
        }
        return $this;
    }

    public function get(string $name): ?string {
        $lower = strtolower($name);  
        if (!isset($this->fieldsLC[$lower])) {
            return null;
        }
        $key = $this->fieldsLC[$lower];
        return $this->fields[$key] ?? null;
    }

    public function each(): \Generator {
        foreach ($this->fields as $name => $value) {
            yield $name => $value;
        }
    }

    // iterator implementation

    public function rewind(): void {
        reset($this->fields);
    }
    
    public function current(): mixed {
        return current($this->fields);
    }
    
    public function key(): mixed {
        return key($this->fields);
    }
    
    public function next(): void {
        next($this->fields);
    }
    
    public function valid(): bool {
        return key($this->fields) !== null;
    }

}
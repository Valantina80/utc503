<?php
namespace LambertAnne_France;
use XMLReader;
use DOMDocument;
use stdClass;
// https://www.php.net/manual/en/class.xmlreader.php
// "This is my new child of XML parsing method  based on my and yours modification."
/**
 * transforme un fichier xml en liste d'objets
 */
class XmlParser extends Parser
{
    private $xml;
    private $name;
    /**
     * XmlParser constructor.
     */
    public function __construct(string $name)
    {
        $this->xml= new XMLReader();
        $this->name=$name;
        parent::__construct($this->name);
    }
    /**
     * transforme la ligne de donnees en objet
     * @param array $ligne
     * @return stdClass
     */
    public function transformeDonneesObjet(array $ligne)
    {
        $o=new stdClass;
        for($i=0; $i < count($ligne); $i++)
        {
            $entete=$this->entete()[$i];
            $o->$entete=$ligne[$i];
        }
        return($o);
    }
    /**
     * transforme les noms des noeuds en tableau
     * @return array
     */
    public function entete()
    {
        $node = simplexml_load_string(file_get_contents($this->name));
        $entete = [];
        $cle = "";
        foreach($node as $key=>$value){
            if($cle == ""){
                $cle = $key;
            }
            if($cle == $key){
                foreach($value as $attr=>$val){
                    if(!in_array($attr, $entete)){
                        array_push($entete, $attr);
                    }
                }
            }else{
                throw new ErrorException("Format XML incorrect");
            }
            $cle = $key;
        }
        return $entete;
    }
    /**
     * transforme les donnees du fichier en tableau d'objets
     * @return array
     */
    public function donnees()
    {
        $doc=new DOMDocument;
        $mesObjets=array();

        if (!$this->xml->open($this->name)) {
            die("Impossible d'ouvrir '".$this->name."'");
        }

        while($this->xml->read()) {
            if ($this->xml->nodeType == XMLReader::ELEMENT && $this->xml->name == 'personne') {
                $node = simplexml_import_dom($doc->importNode($this->xml->expand(), true));
                $tabDonnees=array();
                foreach($node as $ligne){  
                    array_push($tabDonnees, $ligne->__toString()); 
                }
                $monObjet = $this->transformeDonneesObjet($tabDonnees);
                array_push($mesObjets,$monObjet);
            }    
        }
        $this->xml->close();
        return($mesObjets);
        
    }
   

}


?>
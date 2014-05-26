<?

//https://github.com/hasbridge/php-json-schema/tree/master/src#
include 'src/Json/Validator.php';
//include 'tests/JsonValidatorTest.php';

use Json\Validator;

$someJson = '
{
     "timestamp":1,
     "InputDataName": "vmstat",
     "argSize": "6",
     "argName":["procs","memory","swap", "io", "system", "cpu"],
	"date":
	{
	"day":"12",
	"month":"05",
	"time":"03:24:26",
	"year":"2014",
	"type":"UTC"
	},

    "procs"  :
     {
	"argOpSize":"2",
	"argOp":["r","b"],
         "r": "0",
         "b": "0"
     },
     "memory"  :
     {
	"argOpSize":"4",
	"argOp":["swp","free", "buff", "cache"],
         "swpd": "0",
         "free": "907",
         "buff": "8",
         "cache": "38"
     },
     "swap"  :
     {
	"argOpSize":"2",
	"argOp":["si","so"],
         "si": "0",
         "so": "0"
     },

     "io"  :
     {
	"argOpSize":"2",
	"argOp":["bi","bo"],
         "bi": "0",
         "bo": "0"
     },
     "system"  :
     {
	"argOpSize":"2",
	"argOp":["in","cs"],
         "in": "12",
         "cs": "7"
     },
     "cpu"  :
     {
	"argOpSize":"4",
	"argOp":["us","sy", "id", "wa"],
         "us": "1",
         "sy": "0",
         "id": "99",
         "wa": "0"
     },


 "InstanceToken":"fiBO6wLDMaSTJp5McOM9y.DUTfPPLErbyca9Omr.EJJiNMVgW56wu"}';
$jsonObject = json_decode($someJson);

//$validator = new JsonValidator('example/book.json');
$validator = new Validator('vmstat.json');

$validator->validate($jsonObject);

?>

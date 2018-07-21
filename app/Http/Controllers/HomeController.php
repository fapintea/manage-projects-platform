<?php

namespace Diploma\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

/**
 *  @author Fabian Emanuel Pintea
 *  Bachelor's degree project ACS UPB 2018 
 */
class HomeController extends Controller
{
    protected $USER_NAME;
    protected $TEACHER_NAME;
    protected $USER_GROUP;
    protected $USER_EMAIL;
    protected $USER_PROJECT;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function my_profile() {

        return view('my_profile');
    }

    public function update_email(Request $request) {

        $user = Auth::user();
        $user->email = $request->email;
        $user->update();

        return response()->json([
            "success" => true,
            "message" => "E-mailul a fost actualizat cu succes!"
        ]);
    }
    public function dotsRow($section, $pStyle, $nr) {

        for($i = 0; $i < $nr; $i++)
            $section->addText(
                str_repeat(".", 167),
                array('name' => 'Times New Roman', 'size' => 11),
                $pStyle
            );
    }

    public function generateDiplomaFile() {

        // Init constants
        $this->USER_NAME = ucwords(strtolower(Auth::user()->name));
        if (isset(Auth::user()->project))
            $this->TEACHER_NAME = ucwords(strtolower(Auth::user()->project->teacher->name));
        else 
            $this->TEACHER_NAME = null;
        $this->USER_GROUP = Auth::user()->group;
        $this->USER_EMAIL = Auth::user()->email;
        $this->USER_PROJECT = Auth::user()->project;

        // Creating the new document...
        $file = new \PhpOffice\PhpWord\PhpWord();

        $sectionStyle = array(
            'orientation' => 'portrait',
            'marginTop' => 200,
            'marginLeft' => 1500,
            'marginRight' => 1200,
            'name' => 'Tiems New Roman',
            'size' => 10
        );

        $pStyle = array(
            'spaceAfter' => 0
        );

        // Adding an empty Section to the document...
        $section = $file->addSection($sectionStyle);

        // Add header to the page
        $header = $section->addHeader();
        $header->firstPage();
        $table = $header->addTable();
        $table->addRow();
        $cell = $table->addCell(6500);
        $cell->addText(
            'Universitatea POLITEHNICA din Bucureşti,',
            array('name' => 'Times New Roman', 'size' => 10),
            $pStyle
        );
        $cell->addText(
            'Facultatea de Automatică şi Calculatoare,',
            array('name' => 'Times New Roman', 'size' => 10),
            $pStyle
        );
        $cell->addText(
            'Departamentul de Calculatoare',
            array('name' => 'Times New Roman', 'size' => 10),
            $pStyle
        );
        $table->addCell(3000)->addImage(public_path() . '/img/csed.png',
            array(
                'width' => 70,
                'height' => 30,
                'valign' => 'center',
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::END
            )
        );
        $section->addTextBreak(1, null, $pStyle);

        // Add name, group, contact info
        $section->addText(
            'Nume student: ' . $this->USER_NAME,
            array('name' => 'Times New Roman', 'size' => 11),
            array('spaceAfter' => 50)
        );
        $section->addText(
            'Grupă: ' . $this->USER_GROUP,
            array('name' => 'Times New Roman', 'size' => 11),
            array('spaceAfter' => 50)
        );
        $section->addText(
            'Date de contact: E-mail: ' . $this->USER_EMAIL . '         Telefon: ......................................',
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addTextBreak(2, null, $pStyle);

        // Add title
        $section->addText(
            'FIŞA',
            array('name' => 'Times New Roman', 'size' => 13, 'bold' => true),
            array('align' => 'center', 'spaceAfter' => 0)
        );
        $section->addText(
            'proiectului de licenţă',
            array('name' => 'Times New Roman', 'size' => 13, 'bold' => true),
            array('align' => 'center', 'spaceAfter' => 0)
        );
        $section->addTextBreak(1, null, $pStyle);

        // Add project title
        $section->addText(
            '1. Titlul proiectului: ' . ($this->USER_PROJECT == null ? str_repeat(".", 133) :  $this->USER_PROJECT->title),
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        if ($this->USER_PROJECT == null) {
            $this->dotsRow($section, $pStyle, 1);
        }
        $section->addTextBreak(1, null, $pStyle);

        // Add project specification
        $section->addText(
            '2. Specificarea temei:',
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        if ($this->USER_PROJECT != null) {
            $section->addTextBreak(1, null, $pStyle);
            $section->addText(
                $this->USER_PROJECT->description,
                array('name' => 'Times New Roman', 'size' => 11),
                $pStyle
            );
        } else {
            $this->dotsRow($section, $pStyle, 6);
        }
        $section->addTextBreak(1, null, $pStyle);

        // Add project content
        $section->addText(
            '3. Conținutul lucrării scrise (cuprins preliminar):',
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $this->dotsRow($section, $pStyle, 7);
        $section->addTextBreak(1, null, $pStyle);

        // Add project references
        $section->addText(
            '4. Bibliografie minimală  (se vor preciza minim 3 titluri):',
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        if ($this->USER_PROJECT != null) {
            $section->addTextBreak(1, null, $pStyle);
            for($i = 0; $i < 3; $i++) {
                $section->addText(
                    $this->USER_PROJECT->references[$i],
                    array('name' => 'Times New Roman', 'size' => 11),
                    $pStyle
                );
            }
        } else {
            $this->dotsRow($section, $pStyle, 3);
        }
        $section->addTextBreak(1, null, $pStyle);

        // Add project reminder
        $section->addText(
            '5. Proiectul va fi finalizat și predat de către student, până cel târziu cu o săptămână înainte de începutul sesiunii de susținere a proiectulului de licență.',
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addTextBreak(1, null, $pStyle);

        // Adding bottom namings
        $section->addText(
            "Decan,	" . str_repeat("\t", 7) . "Director Departament,",
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addText(
            "Prof.dr.ing. Adina Florea" . str_repeat("\t", 5) . "Prof.dr.ing. Mariana Mocanu",
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addTextBreak(1, null, $pStyle);

        $section->addText(
            str_repeat(".", 40) . str_repeat("\t", 5) . str_repeat(".", 40),
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addTextBreak(1, null, $pStyle);

        $section->addText(
            "Coordonator," . str_repeat("\t", 7) . "Student,",
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );
        $section->addText(
            $this->TEACHER_NAME . str_repeat("\t", $this->TEACHER_NAME != null ? 4 : 8) . $this->USER_NAME,
            array('name' => 'Times New Roman', 'size' => 11, 'italic' => true),
            $pStyle
        );
        $section->addTextBreak(1, null, $pStyle);

        $section->addText(
            str_repeat(".", 40) . str_repeat("\t", 5) . str_repeat(".", 40),
            array('name' => 'Times New Roman', 'size' => 11),
            $pStyle
        );

        // Saving DOCX file
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($file, 'Word2007');

        try {
            $objWriter->save(storage_path('Fişă de diplomă.docx'));
        } catch (Exception $e) {
        }

        return response()->download(storage_path('Fişă de diplomă.docx'));
    }
}

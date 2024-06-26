<?php

namespace App\Http\Controllers;

use App\Models\Vacina;
use Illuminate\Http\Request;

class VacinaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // echo 'aki';
        // die;
        $vacinas = Vacina::get();
        return response()->json($vacinas);
    }

    public function index1($cpf)/*index do android*/
    {
        // Busca as vacinas associadas ao CPF
        $vacinas = Vacina::where('cpf', $cpf)->get();

        // Verifica se as vacinas foram encontradas
        if ($vacinas->isEmpty()) {
            return response()->json(['error' => 'Nenhuma vacina encontrada para este CPF'], 404);
        }

        // Retorna a lista de vacinas como resposta JSON
        return response()->json($vacinas);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dados = $request->except('_token');

        // print_r($dados);die;
        // $dados['dataproximadose'];
        // $dados['dataultimadose'];

        $validatedData = $request->validate([
            'cpf' => 'required|string|size:11',
            'dataproximadose' => 'required|date|after_or_equal:dataultimadose',
            'dataultimadose' => 'required|date|before_or_equal:today',
            'descricao' => 'required|string',
            'nomedavacina' => 'required|string',
        ]);

        // Cria uma nova vacina com os dados validados
        $vacina = Vacina::create($dados);

        // Retorna a vacina recém-criada em formato JSON
        return response()->json($vacina, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'cpf' => 'required|string|size:11',
            'dataproximadose' => 'required|date|after_or_equal:dataultimadose',
            'dataultimadose' => 'required|date|before_or_equal:today',
            'descricao' => 'required|string',
            'nomedavacina' => 'required|string',
        ]);

        // Encontrar o paciente pelo ID
        $vacina = Vacina::findOrFail($id);

        // Atualizar os dados do paciente com base nos dados recebidos na requisição
        $vacina->update([
            'nomedavacina' => $request->input('nomedavacina'),
            'cpf' =>$request->input('cpf'),
            'descricao' => $request->input('descricao'),
            'idade' => $request->input('idade'),
            'dataultimadose' => $request->input('dataultimadose'),
            'dataproximadose' => $request->input('dataproximadose'),
            // Adicione os outros campos que você precisa atualizar
        ]);

        // Retornar uma resposta de sucesso ou a representação atualizada do paciente
        return response()->json($vacina, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $vacina = Vacina::findOrFail($id);
        // // Deletar o paciente
        // $vacina->delete();
        // Retornar uma resposta de sucesso
        // return response()->json(['message' => 'Vacina deletado com sucesso'], 200);


        try {
            $vacina = Vacina::findOrFail($id);
            $vacina->delete();

            return response()->json(['message' => 'Vacina deletado com sucesso', 'sucesso' => true], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Vacina não encontrado', 'sucesso' => false], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao deletar Vacina', 'sucesso' => false, 'erro' => $e->getMessage()], 500);
        }
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $utilizadores = User::all();
        return $this->showAll($utilizadores);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $regras = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' =>'required|min:8|confirmed'
        ];
        $this -> validate($request,$regras);

        $campos = $request->all(); //campos iguais a tudo que venha com o pedido (request)
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::UTILIZADOR_NAO_VERIFICADO;
        $campos['verification_token'] = User::gerarVerificationToken();
        $campos['admin'] = User::UTILIZADOR_REGULAR;
        $utilizador = User::Create($campos); //instância de utilizador por via do método Create - atribuição massiva array campos

        return $this->showOne($utilizadores,201);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) //Injeção implicita no parâmetro, o $id do user é considerado como válido
    {
         return $this->showOne($user);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $regras = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' =>'min:8|confirmed',
            'admin' => 'in:'. User::UTILIZADOR_ADMIN . ',' . User::UTILIZADOR_REGULAR,
        ];
        $this -> validate($request,$regras);
        if($request->has('name')){
                $user->name = $request->name; //O atributo name é igual ao valor recebido pelo pedido (request)
        }
        if($request->has('email') && $user->email != $request->email) {
                 $user->verified = User::UTILIZADOR_NAO_VERIFICADO;
                 $user->verification_token = User::gerarVerificationToken();
                 $user->email = $request->email; //O atributo email é igual ao valor recebido pelo pedido (request)
        }
        if($request->has('password')){
                $user->password = bcrypt($request->password);
        }
        if($request->has('admin')){
            if($user->utilizador_Verificado()){
                return $this->errorResponse('Apenas os utilizadores verificados podem alterar o seu valor de administrador',409); //código 409 temos um conflito com o request do utilizador
            }
            $user->admin = $request->admin;
        }
        if(!$user->isDirty()){ //isDirty() determina se o modelo ou um dado atributo foi modificado
             return $this->errorResponse('Deve especificar-se pelo menos um valor diferente para atualizar',422); //código 422 temos um utilizador com a designação malformada
        }
        $user->save();
         return $this->showOne($utilizadores);
     }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
         return $this->showOne($user);
    }
}
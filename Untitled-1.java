if(auth()->user()->status =='Active'){
    if(auth()->user()->accesstype =='Cashier'){
        
    }elseif(auth()->user()->accesstype =='Renters'){
        
    }elseif(auth()->user()->accesstype =='Supervisor'){
        
    }elseif(auth()->user()->accesstype =='Administrator'){
        
    }
}else{
    return view('welcome');
}
        
public function loaddata(){

}

public function storedata(){

}

public function updatedata(){

}

public function destroydata(){

}


/*
Programa: ListenRTU.js
Propósito: Programa con función de Middleware en el servidor. Se encarga de escuchar los mensajes enviados del RTU al servidor. Al mismo tiempo
inserta los datos que recibió a la base de datos. 
*/
/*Inicia código extra de Ariel*/
var sys = require('sys')
var exec = require('child_process').exec;
function puts(error, stdout, stderr) { 

//	sys.puts(stdout);
	sys.print('stdout: ' + stdout);
  	sys.print('stderr: ' + stderr);
	if (error !== null) {
    console.log('exec error: ' + error);
  }
  
}
/*Termina codigo extra de Ariel...*/


var mysql      = require('mysql');

//Parametros de conexion al servidor
var dbParams = {
  host     : '127.0.0.1',
  user     : 'cedittec',
  password : 'server',
  database : 'biogas' 
}
//creacion de la variable de conexion
var connection;
//ejecutar funcion para correr servidor
listenToRTU();

//Servidor que escucha las conexiones del RTU.
function listenToRTU(){
	try{
		console.log("hola");
		//funcion asincrona para mandar datos a procesar cuando se reciben
	require('net').createServer(function (socket){
		console.log("conectado... escuchando...");
		socket.on('data', function(data){
			//se abre una conexion a la BD cada que se reciben datos.
			connection =  mysql.createConnection(dbParams);
			//console.log(data.toString());

			console.log(data.toString());
			
			/* Linea agregada por Ariel, para imprimir esta cosa en su archivo determinado....*/ 
			//exec("echo '"+data.toString()+"' >> /home/c3d1tt3c_itesm/Desktop/nodeJS/pruebas/$(date '+%A-%d-%m-%Y').txt", puts);

			exec("echo \"$(date '+%H-%M-%S')\" >> /home/c3d1tt3c_itesm/Desktop/nodeJS/pruebas/$(date '+%Y-%m-%d').txt", puts);
			exec("echo '---------' >> /home/c3d1tt3c_itesm/Desktop/nodeJS/pruebas/$(date '+%Y-%m-%d').txt", puts);
			exec("echo '"+data.toString()+"' >> /home/c3d1tt3c_itesm/Desktop/nodeJS/pruebas/$(date '+%Y-%m-%d').txt", puts);
			exec("echo ' ' >> /home/c3d1tt3c_itesm/Desktop/nodeJS/pruebas/$(date '+%Y-%m-%d').txt", puts);



			processData(data.toString());
		});
	})
	.listen(1289);
	}catch(err){
		//notifica si existe un error al momento de crear el servidor
		console.log('Error creando server');
		listenToRTU();
	}
}

// Procesa datos: Si hay datos almacenados en el serial del RTU este mandará varias cadenas de datos en una sola
//las cuales deben ser separadas e insertadas.
function processData(jsonFile)
{	
	//Se separan las cadenas json que fueron almacenadas
	var jsonData = jsonFile.split('}}');
	//iterar en el array de datos
	for(var i=0; i<(jsonData.length-1);i++){
		try{
			//como los caracteres }} se quitaron al hacer el split, se vuelven a concatenar
				jsonData[i] += "}}";
				//parseamos un JSON string a un JavaScript Object
				var processedJSON = JSON.parse(jsonData[i]);
				//enviamos la cadena de datos a ser insetada a la BD.
				insertToDB(processedJSON);	
		}catch(err){
			//notifica si hay error convirtiendo los datos a objeto Javascript
			console.log('Error parseando datos:', err); 
			break;//listenToRTU();
		}
	}

	connection.end();
}

function insertToDB(json){  
	try{   
		var query = 'INSERT INTO controlador_microturbina (date_created, modulo_id, ch4, co2, o2, h2s, va, vb, vc, ia, ib, ic, i_neutral, potencia_total, temperatura, presion, flujo) '+
			'VALUES(now(), 5,'+
				json['CajaNegra']['CH4']+','+
				json['CajaNegra']['CO2']+','+
				json['CajaNegra']['O2']+','+
				json['CajaNegra']['Celda op. 1']+','+
				json['CajaNegra']['Phase AN Voltaje RMS']+','+
				json['CajaNegra']['Phase BN Voltaje RMS']+','+
				json['CajaNegra']['Phase CN Voltaje RMS']+','+
				json['CajaNegra']['PhaseACurrentRMS']+','+
				json['CajaNegra']['PhaseBCurrent RMS']+','+
				json['CajaNegra']['PhaseCCurrent RMS']+','+
				json['CajaNegra']['Neutral Current RMS']+','+
				json['CajaNegra']['Total Average Power']+','+
				json['CajaNegra']['Celda op. 2']+', 0, 0)';
		connection.query(query, function(err, rows, fields) 
		{
			if (err)
			{
				console.log('Error insertando datos de microturbina:', err); 
			}
		});
	/*
		var query = 'INSERT INTO controladorBiofiltro (date, modulo, H2S, CH4, CO2, O2, temperatura, presion, flujo) '+
			'VALUES(now(), 5,'+
				json['controladorMicroturbina']['H2S']+','+
				json['controladorMicroturbina']['CH4']+','+
				json['controladorMicroturbina']['CO2']+','+
				json['controladorMicroturbina']['O2']+','+
				json['controladorMicroturbina']['Temperatura']+','+
				json['controladorMicroturbina']['Presion']+','+
				json['controladorMicroturbina']['Flujo']+')';
		connection.query(query, function(err, rows, fields) 
		{
			if (err)
			{
				console.log('Error insertando datos de biofiltro:', err); 
			}
		});
	*/

		var query = 'INSERT INTO sensor (date_created, modulo_id, ia, ib, ic, va, vb, vc)'+
			'VALUES(now(), 5,'+
				json['Analogicas']['V1']+','+
				json['Analogicas']['V2']+','+
				json['Analogicas']['V3']+','+
				json['Analogicas']['I1']+','+
				json['Analogicas']['I2']+','+
				json['Analogicas']['I3']+')';
		connection.query(query, function(err, rows, fields) 
		{
			if (err)
			{
				console.log('Error insertando datos de sensor:', err); 
			}
		});
	}catch(err){
		//notifica si hay un error a nivel de este programa, o sea al ejecutar la funcion
		console.log('Error insertando datos:', err); 
		listenToRTU();
	}
}	
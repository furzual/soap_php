<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>API SOAP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@1.0.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div style="margin: 1% 2% 0">
    <div class="columns">
        <div class="column">
            <h1 class="subtitle">Configuración</h1>
            
            <form action="sendrequest.php">
                <div class="select is-multiple field">
                    <select name="environment-sel" id="env-select">
                      <option value="test_opt">TEST</option>
                      <option value="www2_opt">W2</option>
                      <option value="www5_opt">W5</option>
                    </select>
                </div>
                <input type="submit">
            </form>
    
            <div class="field">
                <label class="label">StoreID</label>
                <div class="control">
                <input id="storeid_inp" class="input" type="number" placeholder="62666666" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Username</label>
                <div class="control">
                <input id="user_inp" class="input" type="text" placeholder="********" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Userpass</label>
                <div class="control">
                <input id="upass_inp" class="input" type="text" placeholder="********" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Certpass</label>
                <div class="control">
                <input id="cpass_inp" class="input" type="text" placeholder="********" />
                </div>
            </div>
            
            <div class="file has-name">
                <label class="file-label">
                    <input class="file-input" type="file" id="fileInput" name="resume" />
                    <span class="file-cta">
                    <span class="file-icon">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="file-label"> Choose a file… </span>
                    </span>
                    <span class="file-name"> P12 File </span>
                </label>
            </div>
        </div>
        <div class="column">
            <h1 class="subtitle">Información de pago</h1>
            <div class="select is-multiple field">
                <select name="solution-sel" id="sol-select">
                  <option value="test_opt">Venta directa</option>
                  <option value="www2_opt">MSI</option>
                  <option value="www5_opt">PREAUTH</option>
                </select>
            </div>
    
            <div class="field">
                <label class="label">Monto</label>
                <div class="control">
                  <input value=62666666 id="amount_inp" class="input" type="number" placeholder="666.00" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">Número de tarjeta</label>
                <div class="control">
                  <input value=4147463011110059 id="card_inp" class="input" type="number" placeholder="xxxx xxxx xxxx xxxx" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Mes</label>
                <div class="control">
                  <input id="expm_inp" class="input" type="number" placeholder="********" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Año</label>
                <div class="control">
                  <input id="expy_inp" class="input" type="number" placeholder="********" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">CVV</label>
                <div class="control">
                  <input id="upass_inp" class="input" type="number" placeholder="xxx" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">
                    3DS  
                    <input id="3ds_inp" type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
    
            <div class="field">
                <form action="sendRequest.php">
                    <button class="button">Enviar</button>
                </form>
            </div>
    
        </div>
        <div class="column">
            <h1 class="subtitle">Petición</h1>
            <textarea id="requestArea" class="textarea is-link" rows="15" placeholder="Request"></textarea>
        </div>
        <div class="column">
            <h1 class="subtitle">Respuesta</h1>
            <textarea id="responseArea" class="textarea is-link" rows="15" placeholder="Response"></textarea>
        </div>
      </div>
    </div>
</body>
</html>
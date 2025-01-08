<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="./sendrequest.php" method="post">
        <select name="environment-sel">
            <option value="test_opt">TEST</option>
            <option value="www2_opt">W2</option>
            <option value="www5_opt">W5</option>
        </select>

        <div class="field">
            <label class="label">StoreID</label>
            <div class="control">
            <input value=62666666 name="storeid_inp" class="input" type="number" placeholder="62666666" />
            </div>
        </div>

        <div class="field">
            <label class="label">Username</label>
            <div class="control">
            <input value="WS62666666._.5" name="user_inp" class="input" type="text" placeholder="********" />
            </div>
        </div>
        
        <div class="field">
            <label class="label">Userpass</label>
            <div class="control">
                <input value="Password123$" name="upass_inp" class="input" type="password" placeholder="********" />
            </div>
        </div>

        <div class="field">
            <label class="label">Certpass</label>
            <div class="control">
            <input value="Password123$" name="cpass_inp" class="input" type="password" placeholder="********" />
            </div>
        </div>

        <div class="field">
            <label class="label">Certpath</label>
            <div class="control">
            <input value="C:\Users\FISERV\Documents\SOAP_cert\WS62666666._.5.p12" name="cpath_inp" class="input" type="text" placeholder="********" />
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
                <label class="label">TermUrl</label>
                <div class="control">
                  <input value='https://webhook.site/9fbc1448-3141-46f9-b460-a2579fdb1c02' name="turl_inp" class="input" type="text" placeholder="484" />
                </div>
            </div>

            <div class="field">
                <label class="label">ThreeDSMethodNotificationURL</label>
                <div class="control">
                  <input value='https://webhook.site/9fbc1448-3141-46f9-b460-a2579fdb1c02' name="murl_inp" class="input" type="text" placeholder="484" />
                </div>
            </div>

            <div class="field">
                <label class="label">Currency Code</label>
                <div class="control">
                  <input value=484 name="cur_inp" class="input" type="number" placeholder="484" />
                </div>
            </div>

            <div class="field">
                <label class="label">Monto</label>
                <div class="control">
                  <input value=666 name="amount_inp" class="input" type="number" placeholder="666.00" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">Número de tarjeta</label>
                <div class="control">
                  <input value=4265880000000007 name="card_inp" class="input" type="number" placeholder="xxxx xxxx xxxx xxxx" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Mes</label>
                <div class="control">
                  <input value=11 name="expm_inp" class="input" type="number" placeholder="********" />
                </div>
            </div>
            
            <div class="field">
                <label class="label">Año</label>
                <div class="control">
                  <input value=28 name="expy_inp" class="input" type="number" placeholder="********" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">CVV</label>
                <div class="control">
                  <input value=123 name="cvv_inp" class="input" type="number" placeholder="xxx" />
                </div>
            </div>
    
            <div class="field">
                <label class="label">
                    3DS  
                    <input name="3ds_inp" type="checkbox" checked>
                    <span class="slider round"></span>
                </label>
            </div>
    
        </div>

        <input type="submit">
    </form>
</body>
</html>
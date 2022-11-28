<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
  <head>
    <meta charset="utf8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $brand }} - Verifikasi Berhasil</title>
    <style type="text/css">
      @font-face {
        font-family: 'Ubuntu';
        font-style: normal;
        font-weight: 400;
        src: local('Ubuntu Regular'), local('Ubuntu-Regular'), url(http://fonts.gstatic.com/s/ubuntu/v14/4iCs6KVjbNBYlgoKfw7z.ttf) format('truetype');
      }
    </style>
  </head>
  <body style="box-sizing:border-box;margin:0;padding:0;width:100%;color:#333;word-break:break-word;-webkit-font-smoothing:antialiased;font-family:Ubuntu;background-color: #CCCCCC;">
    <table cellpadding="0" cellspacing="0" role="presentation" style="margin: auto;">
      <tr>
        <td align="center">
          <table cellpadding="0" cellspacing="0" role="presentation" style="background: -moz-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(29,60,113,1)), color-stop(100%, rgba(0,96,175,1))); background: -webkit-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -o-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: -ms-linear-gradient(top, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); background: linear-gradient(to bottom, rgba(29,60,113,1) 0%, rgba(0,96,175,1) 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#1d3c71', endColorstr='#0060af', GradientType=0 ); padding: 36px;-webkit-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);">
            <tr>
              <td align="center" class="align-middle" style="vertical-align:middle !important;">
                <table cellpadding="0" cellspacing="0" role="presentation" class="w-600 sm-w-full" style="width:600px !important;background-color:#fff; border-radius: 10px; -webkit-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); -moz-box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1); box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);">
                  <tr>
                    <td class="align-center pt-40" colspan="2" style="text-align:center;padding-top:50px;">
                    </td>
                  </tr>
                  <tr>
                    <td class="align-center" colspan="2" style="text-align:center;">
                      <strong class="text-40 color-blue bold" style="font-size:40px;line-height:53px;margin:0;font-weight:700;color:#0060af !important;">Verifikasi Berhasil</strong>
                    </td>
                  </tr>
                  <tr>
                    <td class="align-center center-icon" colspan="2" style="padding:33px 0 43px 0;text-align:center;">
                      <img src="{{ $assets }}check-blue.png" width="100" height="100" alt="">
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <p class="text-25 align-center pb-20" style="text-align:center;padding-bottom:20px;font-size:25px;line-height:33px;margin:0;">{{ $username }}</p>
                      <p class="text-16 align-center pb-40" style="text-align:center;padding-bottom:40px;font-size:16px;line-height:21px;margin:0;">Akun anda telah terverifikasi,<br>nikmati kemudahan yang diberikan oleh {{ $brand }}.</p>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="footer-message pb-20" style="padding-bottom:20px;border-top:1px solid #efefef;padding-top:27px;">
                    </td>
                  </tr>
                  <tr class="color-grey" style="color:#888 !important;">
                    <td class="align-top pb-40" style="padding-bottom:40px;vertical-align:top !important;padding-left: 30px;">
                      <p class="text-12" style="font-size:12px;line-height:16px;margin:0;">Butuh bantuan?</p>
                      <p class="text-12 pt-10" style="padding-top:10px;font-size:12px;line-height:16px;margin:0;">Ⓒ <?php echo date("Y"); ?> copyright {{ $brand }}</p>
                      <p class="text-12" style="font-size:12px;line-height:16px;margin:0;">{{ $company }}</p>
                    </td>
                    <td class="align-top align-right" style="text-align:right;vertical-align:top !important;padding-right: 30px;">
                      <span class="footer-logo"><img src="{{ $assets }}logo.png" style="border:0;line-height:100%;vertical-align:middle;width:110px;"></span>
                      <p class="text-12 pt-10" style="padding-top:10px;font-size:12px;line-height:16px;margin:0;"><a target="_blank" href="https://{{ $domain }}/" style="color:#0072bc;text-decoration:none;">{{ $domain }}</a>	</p>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>

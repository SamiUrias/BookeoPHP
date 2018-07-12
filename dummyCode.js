<script
  src="https://code.jquery.com/jquery-3.3.1.min.js"
  integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
  crossorigin="anonymous"></script>
 <script>

var urlAddress = 'https://api.bookeo.com/v2/holds?apiKey=AM3L7A6HLATFKC6JHJTWE41568747JEK1641E57DBC2&secretKey=u5KHhbjfp3ac1RnRBO5kIJu5EAtLfELw';
var dataObject = {
    eventId : '41568X7UUEU1641E5934D6_41568AYY9XE164232C8076_2018-06-22',
    title : 'Title',
    customer : {
        firstName :'John Doe',
        lastName : 'Smith',
        emailAddress : 'dummy@dummy.com',
        phoneNumbers : [
            {
                number:'123456', 
                type:'mobile'
            }
        ]
    },
    participants : {
        numbers : [
            {
                number : 1,
                peopleCategoryId : 'Cadults'
            }
        ]
    },
    productId : '41568X7UUEU1641E5934D6'
};


// Ajax connection
$.ajax({
    type: 'POST',
    url: urlAddress,
    dataType: 'jsonp',
    data: JSON.stringify(dataObject),
    headers: {          
        Accept: "text/plain; charset=utf-8",         
        "Content-Type": "application/json;"   
    },
    success: function(result){
        console.log(result);
    }  
});
</script>

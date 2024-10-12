
$18=InvestmentAndSavings::where('name','Gold 18')->first();
$18->update([
    'amount' =>($gold->price_gram_18k * $18->val)
    ]);
$21=InvestmentAndSavings::where('name','Gold 21')->first();
$21->update([
    'amount' =>($gold->price_gram_21k * $21->val) 
    ]);
$24=InvestmentAndSavings::where('name','Gold 24')->first();
$24->update([
    'amount' =>($gold->price_gram_24k * $24->val) 
    ]);

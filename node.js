const express = require('express');
const stripe = require('stripe')('your_secret_key_here');
const app = express();

app.use(express.json());

// Create payment intent
app.post('/create-payment-intent', async (req, res) => {
  const { amount, currency, customerEmail } = req.body;
  
  try {
    const paymentIntent = await stripe.paymentIntents.create({
      amount: Math.round(amount * 100), // Stripe uses cents
      currency: currency || 'usd',
      receipt_email: customerEmail,
      metadata: {
        integration_check: 'accept_a_payment'
      }
    });
    
    res.json({ clientSecret: paymentIntent.client_secret });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

// Confirm payment and create order
app.post('/confirm-payment', async (req, res) => {
  const { paymentMethodId, amount, customerEmail, items } = req.body;
  
  try {
    // Create customer
    const customer = await stripe.customers.create({
      email: customerEmail,
      payment_method: paymentMethodId,
      invoice_settings: {
        default_payment_method: paymentMethodId
      }
    });
    
    // Create payment intent
    const paymentIntent = await stripe.paymentIntents.create({
      amount: Math.round(amount * 100),
      currency: 'usd',
      customer: customer.id,
      payment_method: paymentMethodId,
      off_session: true,
      confirm: true,
      description: 'FashionHub Purchase'
    });
    
    // Here you would save the order to your database
    const order = {
      id: Date.now().toString(),
      date: new Date().toISOString(),
      items,
      amount,
      status: 'paid',
      paymentIntentId: paymentIntent.id
    };
    
    res.json({ success: true, order });
  } catch (error) {
    res.status(500).json({ error: error.message });
  }
});

app.listen(3000, () => console.log('Server running on port 3000'));
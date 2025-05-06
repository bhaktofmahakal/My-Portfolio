// Serverless function to proxy contact form submissions to InfinityFree
import fetch from 'node-fetch';

export default async function handler(req, res) {
  // Set CORS headers to allow requests from any origin
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'POST, OPTIONS');
  res.setHeader('Access-Control-Allow-Headers', 'Content-Type');
  
  // Handle OPTIONS request (preflight)
  if (req.method === 'OPTIONS') {
    return res.status(200).end();
  }

  // Only allow POST requests
  if (req.method !== 'POST') {
    return res.status(405).json({ success: false, message: 'Method not allowed' });
  }

  try {
    // Get form data from request body
    const { name, email, subject, message } = req.body;
    
    // Validate form data
    if (!name || !email || !subject || !message) {
      return res.status(400).json({ success: false, message: 'All fields are required' });
    }
    
    // Create form data to send to PHP backend
    const formData = new URLSearchParams();
    formData.append('name', name);
    formData.append('email', email);
    formData.append('subject', subject);
    formData.append('message', message);
    
    // Send request to InfinityFree backend
    const response = await fetch('https://utsav.infinityfreeapp.com/process_contact.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: formData.toString(),
    });
    
    try {
      // Try to parse JSON response
      const data = await response.json();
      // Return response to client
      return res.status(response.status).json(data);
    } catch (jsonError) {
      // If JSON parsing fails, return generic success/error message based on status code
      if (response.ok) {
        return res.status(200).json({ success: true, message: 'Message sent successfully!' });
      } else {
        return res.status(response.status).json({ 
          success: false, 
          message: `Failed with status: ${response.status}. Please try again later.` 
        });
      }
    }
  } catch (error) {
    console.error('Error proxying request:', error);
    return res.status(500).json({ 
      success: false, 
      message: 'An error occurred while sending your message. Please try again later.' 
    });
  }
}

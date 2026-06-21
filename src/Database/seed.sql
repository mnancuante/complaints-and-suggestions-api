
INSERT INTO complaints (title, description, status, user_id) VALUES
('Late Delivery', 'My order was supposed to arrive last week but it is still not here.', 'Open', 1),
('Damaged Product', 'The product I received is damaged and unusable.', 'Open', 1),
('Wrong Item', 'I ordered a laptop but received a tablet instead.', 'Open', 1),
('Poor Customer Service', 'I called customer service and they were very unhelpful.', 'Open', 1),
('Billing Issue', 'I was charged twice for my order.', 'Open', 1);

INSERT INTO users (email, password) VALUES ("admin@admin.com", 12345678);